<?php

namespace App\Http\Controllers;

use App\User;
use App\Customer;
use App\CustomerDetail;
use App\CustomerHistory;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Http\Requests\Customer\AjaxEvaluationRequest;
use App\Http\Requests\Customer\AjaxSendMailRequest;
use App\Http\Requests\Customer\ExcelUploadRequest;
use App\Imports\CustomersImport;
use App\Mail\CustomerSatisfactionEmbeddedForm;
use App\Mail\CustomerSatisfactionNotifyUsers;
use App\ModenaDisable;
use Excel;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BaseController;
use Session;

class CustomerSatisfactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa'])->except(['ajaxShortUrl', 'showQuestions', 'ajaxEvaluation']);
    }

    public function register()
    {
        $app_url = BaseController::addSlashToEndOfURL();
        $models = ModenaDisable::first();
        return view('user.customer.register')->with([
            'model' => $models,
            'app_url' => $app_url,
        ]);
    }

    public function showQuestions(int $id, string $type = null)
    {
        $models = ModenaDisable::first();

        $data = Customer::where('is_deleted', false)->findOrFail($id);

        $filename = Session::get('name');

        $customer_details = CustomerDetail::where('is_deleted', false)
            ->where(function ($query) use ($type) {
                if ($type) {
                    $query->where('type', $type);
                }
            })
            ->get();

        $customer_details_count = CustomerDetail::where('is_deleted', false)
            ->where(function ($query) use ($type) {
                if ($type) {
                    $query->where('type', $type);
                }
            })
            ->count();

        return view('user.customer.show_questions')->with([
            'model' => $models,
            'data' => $data,
            'customer_details' => $customer_details,
            'customer_details_count' => $customer_details_count,
            'id' => $id,
            'filename' => $filename,
            'type' => $type,
        ]);
    }

    public function ajaxEvaluation(AjaxEvaluationRequest $request): array
    {
        $data = [];

        $qstn = $request->input('qstn');
        $score = $request->input('score');
        $switch = $request->input('switch');
        $id = $request->input('id');
        $type = $request->input('type');
        $filled_by_name = $request->input('filled_by_name');
        $filled_by_position = $request->input('filled_by_position');
        $customer_name = $request->input('customer_name');
        $customer_location = $request->input('customer_location');
        $comment = $request->input('comment');

        [$score_of_all, $whole_score] = BaseController::calculateWholeScore($score, $qstn);

        $score_of_all = $whole_score ? $score_of_all / (0.4 * $whole_score) * 100 : 0;

        $score_of_all = round($score_of_all, 2);

        $data['score'] = $score_of_all;

        $status = BaseController::calculateStatus($score_of_all);

        if ($switch) {
            $emails = EmailNotification::where('module', 'customer_satisfaction')
                ->where('item', 'Customer Satisfaction new survey response')
                ->where('is_blocked', false)
                ->first();

            if($emails) {
                $email_notification_user_ids = EmailNotificationsUserDetails::where('notification_id', $emails->id)->pluck('user_id')->toArray();
                $email_users = User::where('approved', 1)->whereIn('id', $email_notification_user_ids)->pluck('email')->toArray();
                // $user_names = User::where('approved', 1)->whereIn('id', $email_notification_user_ids)->pluck('name')->toArray();
                // $user_names = implode(',', $user_names);
                // $user_names = rtrim($user_names, ",");
                $app_url = BaseController::addSlashToEndOfURL();
                $email_data = [
                    'filled_by_name' => $filled_by_name,
                    'filled_by_position' => $filled_by_position,
                    'name' => $customer_name,
                    //'names' => $user_names,
                    'app_url' => $app_url,
                    'id' => $id,
                    'location' => $customer_location
                ];
                //foreach($email_users as $u) {
                    Mail::to($email_users)->send(new CustomerSatisfactionNotifyUsers($email_data));
                //}
            }

            CustomerHistory::create([
                'customer_id' => $id,
                'filled_by_name' => $filled_by_name,
                'filled_by_position' => $filled_by_position,
                'comment' => $comment,
                'evaluation_date' => now(),
                'overall_score' => $score_of_all,
                'status' => $status,
                'type' => $type,
                'answers' => $qstn,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return [
            "data" => $data,
            "error" => null,
        ];
    }

    public function ajaxGetCustomerDetails(Request $request): array
    {
        $type = $request->input('type');

        $customer_details = CustomerDetail::where(function ($query) use ($type) {
            if ($type) {
                return $query->where('type', $type);
            }
            return $query;
        })
            ->get();

        $data['customer_details'] = $customer_details;

        return [
            "data" => $data,
            "error" => null,
        ];
    }

    public function history(int $id)
    {
        $data = CustomerHistory::where('customer_id', $id)->get();
        $customer = Customer::where('is_deleted', false)->findOrFail($id);

        $models = ModenaDisable::first();

        // if(Auth::guest()) {
        //     return view('user.customer.no_auth_history')->with([
        //         'model' => $models,
        //         'data' => $data,
        //         'customer' => $customer
        //     ]);
        // } else {
        return view('user.customer.history')->with([
            'model' => $models,
            'data' => $data,
            'customer' => $customer,
        ]);
        //}

    }

    public function shortenUrl($longUrl): string
    {
        try {
            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => 'https://tinyurl.com/api-create.php',
                // You can set any number of default request options.
                'timeout' => 100.0,
            ]);

            $response = $client->post('', [
                'form_params' => [
                    'url' => $longUrl,
                ],
            ]);
        } catch (GuzzleException $e) {
            Log::info('GuzzleException ' . $e->getMessage());
            $response = [];
        }

        return $response->getBody()->getContents();
    }

    public function ajaxShortUrl(Request $request): array
    {
        $url = $request->input('url');
        $type = $request->input('type');
        $id = $request->input('id');

        $url .= 'user/customer/showQstn/' . $id . '/' . $type;

        $shortUrl = $this->shortenUrl($url);

        return [
            'data' => $shortUrl,
            'error' => null,
        ];
    }

    public function ajaxSendMail(AjaxSendMailRequest $request): array
    {
        $id = $request->input('id');
        $type = $request->input('type');
        $customer_emails = $request->input('customer_emails');
        $customer_emails = explode(',', $customer_emails);
        $customer = Customer::where('is_deleted', false)->findOrFail($id);
        $name = ucfirst($customer->customer);

        $app_url = BaseController::addSlashToEndOfURL();
        $company = BaseController::getCompanyFromAppRUL();

        $data = [];
        $data['id'] = $id;
        $data['type'] = $type;
        $data['app_url'] = $app_url;
        $data['name'] = $name;
        $data['company'] = $company;

        Mail::to($customer_emails)->send(new CustomerSatisfactionEmbeddedForm($data));

        return [
            'data' => null,
            'error' => null,
        ];
    }

    public function excelUpload(ExcelUploadRequest $request)
    {
        $rows = Excel::toArray(new CustomersImport, $request->file('excel_data'))[0];
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $email = $row['email'];

                if (empty($row['company']) || empty($row['product']) || empty($email)) {
                    Session::flash('errmsg', 'Company or Product or Email data is empty.Please refer Note for excel upload format');

                    return redirect()->back();
                } else {

                    $data = [
                        'customer' => $row['company'],
                        'location' => $row['location'],
                        'product' => $row['product'],
                        'country_id' => 0,
                        'state_id' => 0,
                        'email' => $email,
                        'phone' => $row['phone'],
                        'customer_survey_emails' => $row['customer_survey_emails'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    //checking if a point for a particular data already exists in database
                    $check = Customer::where('email', $email)->first();
                    if ($check) {
                        Customer::where('email', $email)->update($data);
                    } else {
                        Customer::create($data);
                    }
                }
            }
        }

        if (!File::exists(public_path('/storage/customer_satisfaction/'))) {
            File::makeDirectory(public_path() . '/storage/customer_satisfaction/', 0755, true);
        }

        $name = 'excel_data' . '.' . $request->file('excel_data')->extension();
        $request->file('excel_data')->move(public_path() . '/storage/customer_satisfaction/', $name);

        $models = ModenaDisable::first();

        return redirect()
            ->back()
            ->with('msg', 'Customer List Uploaded successfully')
            ->with('model', $models);

    }
}
