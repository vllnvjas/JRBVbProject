<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Client;

class PageController extends Controller
{
    public function about () {
    $a = 3;
    $b = 5;
    $sum = $a + $b;
    return "Sum is: " .$sum;
}

public function userProfile() {
    $user = User::with('profile')->find(2);

    if (! $user) {
        return 'User not found.';
    }

    $bio = $user->profile?->bio ?? 'No bio available.';

    return $user->name . ' - ' . $bio;
}

public function userPosts() {
    $user = User::with('posts')->find(2);

    if (! $user) {
        return 'User not found.';
    }

    if ($user->posts->isEmpty()) {
        return 'Posts by ' . $user->name . ': none yet.';
    }

    $output = 'Posts by ' . $user->name . ":<br>";

    foreach ($user->posts as $post) {
        $output .= $post->title . ': ' . $post->content . "<br>";
    }

    return $output;
}

public function studentCourses() {
    $student = Student::with('courses')->find(2);

    if (! $student) {
        return 'Student not found.';
    }

    $courses = $student->courses;

    if ($courses->isEmpty()) {
        return $student->fname . ' ' . $student->lname . ': none yet.';
    }

    $output = $student->fname . ' ' . $student->lname . " is enrolled in:<br>";

    foreach ($courses as $course) {
        $output .= $course->course_name . "<br>";
    }

    return $output;
}

    public function maintenance() {
        return view('maintenance', [
            'title' => 'Down for Maintenance',
            'message' => 'The site is temporarily unavailable while we apply updates. Your normal pages will return as soon as maintenance is turned off.',
        ]);
    }

    public function demo() {
        $students = Student::with(['degree', 'userAccount'])->orderBy('id')->get();

        return view('demo', [
            'title' => 'Demo Page',
            'message' => 'This is a demo page to test the layout and styling of the application.',
            'students' => $students,
        ]);
    }
}
