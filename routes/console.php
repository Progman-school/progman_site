<?php

use App\Http\Controllers\DBRebuilder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

// BD rebuilders commands from old DB structure

Artisan::command('rebuild_tags', function () {
    $this->comment(DBRebuilder::rebuildTags());
})->purpose('Moving tags content');

Artisan::command('rebuild_courses', function () {
    $this->comment(DBRebuilder::rebuildCourses());
})->purpose('Moving tags courses data');

Artisan::command('rebuild_users_requests', function () {
    $this->comment(DBRebuilder::rebuildUsersAndRequests());
})->purpose('Moving userRequests data');

Artisan::command('rebuild_certificates', function () {
    $this->comment(DBRebuilder::rebuildCertificates());
})->purpose('Moving certificates data');

Artisan::command('rebuild_all', function () {
    $this->comment(DBRebuilder::rebuildAll());
})->purpose('Moving all data, from the old DB to a new one');
