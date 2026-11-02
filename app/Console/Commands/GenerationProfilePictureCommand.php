<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerationProfilePictureCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-profile-picture-when-null:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will generate avatar for users has not profile picture in the system.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('[Command: generate:avatar] START');
        Log::info('[Command: generate:avatar] START');
        try {
            $usersWithProfilePictureDoNotExists = User::whereNotNull('profile_picture')
                ->orderBy('id')
                ->get();
            foreach ($usersWithProfilePictureDoNotExists as $user) {
                if (!File::exists(public_path('/storage/profile/'. $user->profile_picture))) {
                    Log::info('[Command: generate:avatar] Update profile is null of user id: ' . $user->id . ', name: ' . $user->name . ', email: ' . $user->email);
                    Log::info('[Command: generate:avatar] Previous value: ' . $user->profile_picture);
                    $user->profile_picture = null;
                    $user->save();
                    $this->info('Update profile is null of id: ' . $user->id . ', name: ' . $user->name . ', email: ' . $user->email . ': DONE');
                }
            }

            $usersWithoutProfilePicture = User::whereNull('profile_picture')
                ->orWhere('profile_picture', '=', '')
                ->orderBy('id')
                ->get();
            $textColor = 'FFFFFF';
            $backgroundColors = [
                '1F42AD',
                '1F42AD',
                'F5993D',
                'E64C73',
                'AD858F',
                '9952E0',
                '75ABBD',
                'AD661F',
                'C63939',
                '0D80F2',
                'E87DB3',
                '7594F0',
                '7DA1E8'
            ];

            $this->info('Number of record need to update: ' . $usersWithoutProfilePicture->count());
            // if ($this->confirm('Do you wish to continue?')) {
                $bar = $this->output->createProgressBar(count($usersWithoutProfilePicture));
                $bar->start();
                $userIds = [];

                foreach ($usersWithoutProfilePicture as $user) {
                    Log::info('[Command: generate:avatar] Update user id: ' . $user->id . ', name: ' . $user->name . ', email: ' . $user->email);
                    $backgroundColorIndex = array_rand($backgroundColors);
                    $image = file_get_contents('https://ui-avatars.com/api/?name=' . rawurlencode($user->name) . '&color=' . $textColor . '&background=' . $backgroundColors[$backgroundColorIndex] . '&rounded=true&bold=true');
                    $fileName = $user->employeeid . '_' . time() . '.png';
                    file_put_contents(public_path('storage/profile') . '/' . $fileName, $image);
                    $user->profile_picture = $fileName;
                    $user->save();
                    $userIds[] = $user->id;
                    $this->line('');
                    $this->info('Update user id: ' . $user->id . ', name: ' . $user->name . ', email: ' . $user->email . ': DONE');
                    $bar->advance();
                }

                $bar->finish();

                $this->line('');
                $this->info('The SQL for restore data');
                $this->line('> SQL: UPDATE users SET profile_picture = NULL WHERE id IN (' . implode(',', $userIds) . ');');
                Log::info('The SQL for restore data');
                Log::info('> SQL: UPDATE users SET profile_picture = NULL WHERE id IN (' . implode(',', $userIds) . ');');
            // }
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
        Log::info('[Command: generate:avatar] END');
        $this->line('');
        $this->info('DONE');
    }
}
