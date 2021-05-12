<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    const TOKEN_EXPIRES_IN_HOURS = 1;

    protected $guarded = [];
}
