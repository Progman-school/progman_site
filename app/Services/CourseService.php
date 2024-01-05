<?php

namespace App\Services;

use App\Models\Course;

class CourseService
{
    public static function getCursesList(): array
    {
        $courses = Course::where(["active" => true])->all();
        foreach ($courses as &$course){
            $course["technologies"] = $course->technologies();
        }
        return $courses;
    }
}
