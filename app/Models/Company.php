<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = "companies";

    protected $fillable = [
        'name',
        'country_id'
    ];

    public $timestamps = true;

    public function users() {
        return $this->belongsToMany('App\Models\User', 'company_user', 'user_id', 'company_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
}
