<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseDepartment;
use App\CourseHeading;
use App\CourseHeadingContent;
use App\CourseHeadingContentUser;
use App\CourseLabel;
use App\CourseParticipant;
use App\CourseReview;
use App\Departments;
use App\Http\Requests\Elearning\AddContentRequest;
use App\Http\Requests\Elearning\AddCourseRequest;
use App\Http\Requests\Elearning\AddCourseReviewRequest;
use App\Http\Requests\Elearning\UpdateContentRichTextRequest;
use App\Http\Requests\Elearning\UpdateCourseHeadingContentRequest;
use App\Http\Requests\Elearning\UpdateCourseHeadingRequest;
use App\Http\Requests\Elearning\UpdateCourseRequest;
use App\ModenaDisable;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ElearningController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa']);
    }

    public function getVideoDuration($videoUrl)
    {
        $videoId = $this->getYouTubeVideoId($videoUrl);

        $apiKey = env('YOUTUBE_API_KEY'); // Replace with your actual YouTube Data API key
        $apiUrl = "https://www.googleapis.com/youtube/v3/videos?part=contentDetails&id=$videoId&key=$apiKey";

        $client = new Client();
        $response = $client->get($apiUrl);

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody(), true);

            if (isset($data['items'][0]['contentDetails']['duration'])) {
                $duration = $data['items'][0]['contentDetails']['duration'];
                return $this->formatDuration($duration);
            }
        }

        return false; // Failed to retrieve the video duration
    }

    private function getYouTubeVideoId($videoUrl)
    {
        $pattern = '/(?:youtu\.be\/|youtube(?:-nocookie)?\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})(?:(?:[\?&]t=)(\d+))?/';
        preg_match($pattern, $videoUrl, $matches);

        if (isset($matches[1])) {
            return $matches[1];
        }

        return false; // Invalid YouTube video URL
    }

    private function formatDuration($duration)
    {
        preg_match('/P(\d+D)?T(\d+H)?(\d+M)?(\d+S)?/', $duration, $matches);

        $days = isset($matches[1]) ? intval(substr($matches[1], 0, -1)) : 0;
        $hours = isset($matches[2]) ? intval(substr($matches[2], 0, -1)) : 0;
        $minutes = isset($matches[3]) ? intval(substr($matches[3], 0, -1)) : 0;
        $seconds = isset($matches[4]) ? intval(substr($matches[4], 0, -1)) : 0;

        $totalSeconds = ($days * 24 * 60 * 60) + ($hours * 60 * 60) + ($minutes * 60) + $seconds;

        return $totalSeconds;
    }

    public function calculateDurationBasedOnRichText($rich_text)
    {
        $total_duration = 0;
        $wordCount = str_word_count(strip_tags($rich_text));
        $averageReadingSpeed = 200;
        $textDuration = ceil($wordCount / $averageReadingSpeed); // in minutes
        $textDuration = $textDuration * 60; // in seconds

        $html = strip_tags($rich_text, '<oembed>');
        preg_match_all('/url="([^"]+)"/', $html, $match);
        $video_urls = $match[1];
        $video_total_duration = 0;

        foreach ($video_urls as $url) {
            if ($this->getYouTubeVideoId($url)) {
                // do something
                $video_total_duration += $this->getVideoDuration($url);
            } else {
                return -1;
            }
        }
        $total_duration = $textDuration + $video_total_duration;
        /**
         * Validation to check if total duration be less than 60 mins(1 hour)
         */
        if ($total_duration > 3600) {
            return -2;
        }

        return $total_duration;
    }

    public function courses()
    {
        $model = ModenaDisable::first();
        $departments = Departments::all();
        $courses = Course::where('is_deleted', false)->where(function ($query) {
            if (!Gate::allows('elearning-add-content')) {
                $query->where('published', 1);
            }
        })->get();

        $icons_array = ['fab fa-html5', 'fab fa-css3', 'si si-vector', 'si si-rocket', 'si si-user-following', 'si si-target',
            'si si-support', 'si si-social-twitter', 'si si-briefcase', 'si si-camcorder', 'si si-chemistry'];
        $colors_array = ['bg-city', 'bg-flat', 'bg-amethyst', 'bg-smooth', 'bg-modern', 'bg-warning', 'bg-success', 'bg-info', 'bg-danger',
            'bg-gray-dark', 'bg-primary'];
        $random_icon = array_rand($icons_array);
        $random_color = array_rand($colors_array);
        $course_ids = Course::where('is_deleted', false)->pluck('id')->toArray();
        $course_ids = implode(',', $course_ids);

        return view('user.elearning.courses')->with([
            'departments' => $departments,
            'courses' => $courses,
            'random_icon' => $random_icon,
            'random_color' => $random_color,
            'icons_array' => $icons_array,
            'colors_array' => $colors_array,
            'course_ids' => $course_ids,
            'model' => $model,
        ]);
    }

    public function course(int $id)
    {
        $course = Course::where('is_deleted', false)->with('comments.replies')->findOrFail($id);
        $course_labels = CourseLabel::where('is_deleted', false)->where('courses_id', $id)->get();
        $course_heading_content_users = CourseHeadingContentUser::where('user_id', Auth::user()->id)->pluck('course_heading_content_id')->toArray();
        $course_heading_content_users = array_unique($course_heading_content_users);
        $participants = CourseParticipant::where('course_id', $id)->whereIn('id', CourseParticipant::select(DB::raw('MAX(id) as id'))->groupBy('email')->get()->toArray())->latest()->get();
        $all_course_heading_contents = CourseHeadingContent::where('is_deleted', false)->whereHas('courseHeading', function ($query) use ($id) {
            $query->where('courses_id', $id);
        })->get();
        $course_reviews = CourseReview::where('course_id', $id)->get();
        $course_review_score_array = CourseReview::where('course_id', $id)->pluck('score')->toArray();
        $course_review_score_array = array_filter($course_review_score_array);
        $average_score = count($course_review_score_array) ? round(array_sum($course_review_score_array) / count($course_review_score_array), 2) : 0;

        $model = ModenaDisable::first();

        return view('user.elearning.course')->with([
            'id' => $id,
            'course' => $course,
            'course_labels' => $course_labels,
            'course_reviews' => $course_reviews,
            'course_heading_content_users' => $course_heading_content_users,
            'participants' => $participants,
            'all_course_heading_contents' => $all_course_heading_contents,
            'average_score' => $average_score,
            'model' => $model,
        ]);
    }

    public function addCourse(AddCourseRequest $request)
    {
        $name = $request->input('name');
        $labels = $request->input('labels');
        $departments = $request->input('departments');
        $cover = $request->file('cover');
        $labels = explode(',', $labels);

        $cer = null;
        if ($request->hasFile('cover')) {
            $new_file_name = $cover->getClientOriginalName();
            $destination_path = public_path('storage/elearning');
            $cover->move($destination_path, $new_file_name);
            $file = $new_file_name;
            $cer = $file;
        }

        $course = Course::create([
            'name' => $name,
            'cover_image' => $cer,
        ]);

        if ($departments && count($departments)) {
            for ($i = 0; $i < count($departments); $i++) {
                CourseDepartment::create([
                    'departments_id' => $departments[$i],
                    'courses_id' => $course->id,
                ]);

            }
        }

        if ($labels && count($labels)) {
            for ($i = 0; $i < count($labels); $i++) {
                if ($labels[$i] != "") {
                    CourseLabel::create([
                        'label' => $labels[$i],
                        'courses_id' => $course->id,
                    ]);
                }

            }
        }

        return [
            'data' => null,
            'error' => null,
        ];
    }

    public function updateContentRichText(UpdateContentRichTextRequest $request)
    {
        $error = null;
        $content_id = $request->input('content_id');
        $rich_text = $request->input('rich_text');
        $textDuration = $this->calculateDurationBasedOnRichText($rich_text);

        if ($textDuration === -1) {
            $error = "The video url is not valid!";
        } else if ($textDuration === -2) {
            $error = "Video duration + Text duration must be less than 1 hour!";
        }

        if ($error == null) {
            CourseHeadingContent::where('is_deleted', false)->where('id', $content_id)->update([
                'rich_text' => $rich_text,
                'duration' => $textDuration,
            ]);

            $heading_id = CourseHeadingContent::where('is_deleted', false)->where('id', $content_id)->value('course_headings_id');

            $sum = CourseHeadingContent::where('is_deleted', false)->where('course_headings_id', $heading_id)->sum('duration');
            $course_heading = CourseHeading::where('is_deleted', false)->where('id', $heading_id)->firstOrFail();

            $course_heading->duration = $sum;
            $course_heading->save();

            $course_duration_sum = CourseHeading::where('is_deleted', false)->where('courses_id', $course_heading->courses_id)->sum('duration');

            Course::where('is_deleted', false)->where('id', $course_heading->courses_id)->update([
                'duration' => $course_duration_sum,
            ]);

        }

        return [
            'data' => null,
            'error' => $error,
        ];
    }

    public function publishCourse(int $id)
    {
        $course = Course::where('is_deleted', false)->findOrFail($id);
        $course->published = 1;
        $course->save();

        return [
            'data' => null,
            'error' => null,
        ];
    }

    public function unpublishCourse(int $id)
    {
        $course = Course::where('is_deleted', false)->findOrFail($id);
        $course->published = 0;
        $course->save();

        return [
            'data' => null,
            'error' => null,
        ];
    }

    public function deleteCourse(int $id)
    {
        $course = Course::where('is_deleted', false)->findOrFail($id);
        $course->is_deleted = true;
        $course->save();

        CourseDepartment::where('courses_id', $id)->delete();
        CourseLabel::where('courses_id', $id)->update([
            'is_deleted' => true,
        ]);

        $course_heading = CourseHeading::where('is_deleted', false)->where('courses_id', $id)->first();
        if ($course_heading) {
            CourseHeadingContent::where('course_heading_id', $course_heading->id)->update([
                'is_deleted' => true,
            ]);
            $course_heading->update([
                'is_deleted' => true,
            ]);

        }

        return [
            'data' => null,
            'error' => null,
        ];
    }

    public function updateCourse(UpdateCourseRequest $request)
    {
        $id = $request->input('id');
        $name = $request->input('edit_name');
        $labels = $request->input('edit_labels');
        $departments = $request->input('edit_departments');
        $cover = $request->file('edit_cover');
        $labels = explode(',', $labels);

        $course = Course::where('is_deleted', false)->findOrFail($id);

        $cer = null;
        if ($request->hasFile('edit_cover')) {

            if (Storage::exists("elearning/" . $course->cover_image)) {
                Storage::delete("elearning/" . $course->cover_image);
            }

            $new_file_name = $cover->getClientOriginalName();
            $destination_path = public_path('storage/elearning');
            $cover->move($destination_path, $new_file_name);
            $file = $new_file_name;
            $cer = $file;
        } else {
            $cer = $course->cover_image;
        }

        $course->name = $name;
        $course->cover_image = $cer;
        $course->save();
        $course->departments()->sync($departments);

        if ($labels && count($labels)) {
            CourseLabel::where('courses_id', $id)->delete();
            for ($i = 0; $i < count($labels); $i++) {
                CourseLabel::create([
                    'label' => $labels[$i],
                    'courses_id' => $id,
                ]);

            }
        }

        return [
            'data' => null,
            'error' => null,
        ];

    }

    public function addContent(int $id)
    {
        $model = ModenaDisable::first();
        $course = Course::where('is_deleted', false)->findOrFail($id);
        $course_headings = CourseHeading::where('is_deleted', false)->where('courses_id', $id)->get();

        return view('user.elearning.add_content')->with([
            'id' => $id,
            'course' => $course,
            'course_headings' => $course_headings,
            'model' => $model,
        ]);
    }

    public function insertContent(AddContentRequest $request)
    {
        $models = ModenaDisable::first();

        $data = $request->validated();

        $id = $data['course_id'];
        $topic_title = $data['topic_title'];
        $rich_text = $data['rich_text'];
        $content_name = $data['content_name'];

        $course_headings = CourseHeading::where('is_deleted', false)->where('courses_id', $id)->get();

        if ($topic_title != null) {
            if (count($course_headings)) {
                $course_heading_found = CourseHeading::where('is_deleted', false)->where('courses_id', $id)->where('heading', $topic_title)->first();
                if (!$course_heading_found) {
                    CourseHeading::create([
                        'courses_id' => $id,
                        'heading' => $topic_title,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                CourseHeading::create([
                    'courses_id' => $id,
                    'heading' => $topic_title,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $heading_id = CourseHeading::where('is_deleted', false)->where('heading', $topic_title)->value('id');
        } else {
            $heading_id = $data['heading'];
        }

        $found = CourseHeadingContent::where('is_deleted', false)->where('name', $content_name)->first();
        $textDuration = $this->calculateDurationBasedOnRichText($rich_text);
        if ($textDuration === -1) {
            Session::flash('error', 'The video url is not valid!');
        } else if ($textDuration === -2) {
            Session::flash('error', 'Video duration + Text duration must be less than 1 hour!');
        }

        if ($textDuration === -1 || $textDuration === -2) {
            return redirect()->back()->with('model', $models);
        }

        if (!$found) {
            CourseHeadingContent::firstOrCreate([
                'course_headings_id' => $heading_id,
                'name' => $content_name,
                'rich_text' => $rich_text,
                'duration' => $textDuration,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        CourseHeadingContent::where('is_deleted', false)->where('course_headings_id', $heading_id)->where('name', $content_name)->update([
            'rich_text' => $rich_text,
            'duration' => $textDuration,
        ]);

        $sum = CourseHeadingContent::where('is_deleted', false)->where('course_headings_id', $heading_id)->sum('duration');
        $course_heading = CourseHeading::where('is_deleted', false)->where('id', $heading_id)->firstOrFail();

        $course_heading->duration = $sum;
        $course_heading->save();

        $course_duration_sum = CourseHeading::where('is_deleted', false)->where('courses_id', $id)->sum('duration');

        Course::where('is_deleted', false)->where('id', $course_heading->courses_id)->update([
            'duration' => $course_duration_sum,
        ]);

        $total_topics = CourseHeadingContent::where('is_deleted', false)->whereHas('courseHeading.course', function ($q) use ($id) {
            return $q->where('courses_id', $id);
        })->count();

        $course_participants = CourseParticipant::where('course_id', $id)->where('email', Auth::user()->email)->get();

        $i = 1;
        $percentage = [];
        foreach ($course_participants as $p) {
            $percentage[$i] = ($i / $total_topics) * 100;
            $percentage[$i] = round($percentage[$i], 2);

            $p->where('id', $p->id)->where('course_id', $id)->update([
                'progress_percentage' => $percentage[$i],
            ]);

            $i++;
        }

        Session::flash('msg', "Content added for the course successfully");

        return redirect()->back()->with('model', $models);
    }

    public function showRichText(int $id)
    {
        $model = ModenaDisable::first();
        $course_heading_content = CourseHeadingContent::where('is_deleted', false)->findOrFail($id);
        $course_headings_id = $course_heading_content->course_headings_id;
        $course_id = CourseHeading::where('is_deleted', false)->where('id', $course_headings_id)->value('courses_id');

        return view('user.elearning.rich_text')->with([
            'course_heading_content' => $course_heading_content,
            'course_id' => $course_id,
            'model' => $model,
        ]);
    }

    public function deleteCourseContent(int $id)
    {
        CourseHeadingContent::where('is_deleted', false)->where('id', $id)->update([
            'is_deleted' => true,
        ]);

        return [
            'data' => null,
            'error' => null,
        ];
    }

    public function updateCourseHeading(UpdateCourseHeadingRequest $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');

        $course_heading = CourseHeading::findOrFail($id);

        $course_heading->heading = $name;
        $course_heading->save();

        return [
            'data' => null,
            'error' => null,
        ];
    }

    public function updateCourseContent(UpdateCourseHeadingContentRequest $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');

        $course_heading_content = CourseHeadingContent::where('is_deleted', false)->findOrFail($id);

        $course_heading_content->name = $name;
        $course_heading_content->save();

        return [
            'data' => null,
            'error' => null,
        ];
    }

    public function deleteCourseHeading(int $id)
    {
        $course_heading_content_builder = CourseHeadingContent::where('is_deleted', false)->where('course_headings_id', $id);
        $course_heading_content = $course_heading_content_builder->count();
        if ($course_heading_content) {
            $course_heading_content_builder->update([
                'is_deleted' => true,
            ]);
        }

        CourseHeading::where('is_deleted', false)->where('id', $id)->update([
            'is_deleted' => true,
        ]);

        return [
            'data' => null,
            'error' => null,
        ];
    }

    public function addLike(int $id)
    {
        Course::where('is_deleted', false)->where('id', $id)->update([
            'like_count' => DB::raw('like_count + 1'),
        ]);

        return [
            'data' => null,
            'error' => null,
        ];

    }

    public function markReadingAsCompleted(int $id)
    {
        $course_heading_content = CourseHeadingContent::where('is_deleted', false)->where('id', $id)->first();
        $course_heading = $course_heading_content ? $course_heading_content->course_headings_id : 0;
        $course_id = $course_heading ? CourseHeading::where('is_deleted', false)->where('id', $course_heading)->value('courses_id') : 0;

        $found = CourseHeadingContentUser::where('user_id', Auth::user()->id)->where('course_heading_content_id', $id)->first();

        if (!$found) {
            CourseHeadingContentUser::create([
                'course_heading_content_id' => $id,
                'user_id' => Auth::user()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $total_completed_topics = CourseHeadingContent::where('is_deleted', false)->whereHas('courseHeadingContentUser', function ($q) use ($id) {
            return $q->where('course_heading_content_id', $id);
        })->count();

        $total_completed_topics = CourseHeadingContentUser::where('user_id', Auth::user()->id)->pluck('course_heading_content_id')->toArray();
        $total_completed_topics = count(array_unique($total_completed_topics));

        $total_topics = CourseHeadingContent::where('is_deleted', false)->whereHas('courseHeading.course', function ($q) use ($course_id) {
            return $q->where('courses_id', $course_id);
        })->count();

        $progress_percentage = ($total_completed_topics / $total_topics) * 100;
        $progress_percentage = round($progress_percentage, 2);

        CourseParticipant::create([
            'email' => Auth::user()->email,
            'course_id' => $course_id,
            'duration' => $course_heading_content->duration,
            'progress_percentage' => $progress_percentage,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [
            'data' => null,
            'error' => null,
        ];

    }

    public function addReview(AddCourseReviewRequest $request)
    {
        $user_email = Auth::user()->email;
        $data = $request->validated();
        $score = $data['score'];
        $course_id = $data['course_id'];

        $found = CourseReview::where('course_id', $course_id)->where('email', $user_email)->first();

        if (!$found) {
            CourseReview::create([
                'course_id' => $course_id,
                'email' => $user_email,
                'score' => $score,
            ]);
        } else {
            CourseReview::where('course_id', $course_id)->where('email', $user_email)->update([
                'score' => $score,
            ]);
        }

        return [
            'data' => null,
            'error' => null,
        ];

    }
}
