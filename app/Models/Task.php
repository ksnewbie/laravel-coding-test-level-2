<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_NOT_STARTED = 'NOT_STARTED';
    const STATUS_IN_PROGRESS = 'IN_PROGRESS';
    const STATUS_READY_FOR_TEST = 'READY_FOR_TEST';
    const STATUS_COMPLETED = 'COMPLETED';

    protected $fillable = [
        'title',
        'description',
        'status',
        'project_id',
        'user_id',
    ];
}
