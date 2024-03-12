<?php

namespace App\Services;

use App\Models\Course;

class CourseService
{
    public static function getAllCourses(?bool $active = null): array
    {
        $conditions = [];
        if (!is_null($active)) {
            $conditions = ["active" => $active];
        }

        return Course::with("technologies")
            ->where($conditions)
            ->orderBy('order')
            ->get()
            ->keyBy('id')
            ->toArray();
    }
}
