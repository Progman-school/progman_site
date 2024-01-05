<?php

namespace App\Services;

use App\Models\Course;

class CourseService
{
    public static function getCoursesList(): array
    {
        return Course::with("technologies")->where(["active" => true])->get();
    }
}
