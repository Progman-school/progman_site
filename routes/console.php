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

// BD rebuilder commands from old DB structure

Artisan::command('rebuild-db {part}', function ($part) {
    $dbRebuilder = new DBRebuilder();
    switch ($part) {
        case 'tags':
            $this->comment($dbRebuilder->rebuildTags());
            break;
        case 'courses':
            $this->comment($dbRebuilder->rebuildCourses());
            break;
        case 'users_requests':
            $this->comment($dbRebuilder->rebuildUsersAndRequests());
            break;
        case 'certificates':
            $this->comment($dbRebuilder->rebuildCertificates());
            break;
        case 'all':
            $this->comment($dbRebuilder->rebuildAll());
            break;
        case 'part-list':
            $this->comment('Available parts: tags, courses, users_requests, certificates, all');
            break;
        default:
            $this->comment('Wrong part name');
    }
})->purpose(
    "Moving data, from the old DB structure to a new one.\n Try part-list to see all available parts"
);
