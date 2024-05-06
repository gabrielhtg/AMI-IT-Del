<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationModel extends Model
{
    use HasFactory;

    protected $table = "notification";

    protected $fillable = [
        'message',
        'ref_link',
        'created_at',
        'to',
        'clicked'
    ];
}