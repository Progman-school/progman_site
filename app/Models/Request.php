<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Request extends Model
{
    const RECEIVED_STATUS = "received";
    const CONFIRMED_STATUS = "confirmed";
    const PROCESSED_STATUS = "processed";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'created_at',
        'updated_at',
        'contact',
        'type',
        'hash',
        'course_id',
        'test_score',
        'application_data',
        'language',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function course(): HasOne
    {
        return $this->hasOne(Course::class);
    }

    private function getRepeats()
    {
        return self::where('contact', $this->contact)
            ->andWhere('course_id', $this->courseId);
    }

    public function getRepeatsCount(): int
    {
        return $this->getRepeats()->count();
    }

    public function getDateOfTheFirstRepeat(): int
    {
        return $this->getRepeats()->first()->created_at;
    }
}
