<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleAccount extends Model
{
    use HasFactory;

    protected $table = 'google_accounts';
    public $incrementing = false;
    protected $fillable = ['google_id', 'json'];
}
