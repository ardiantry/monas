<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
   // use HasFactory;
    protected $fillable = [
    		'id',
			'name',
			'last_name',
			'password',
			'tgl_lahir',
			'tpt_lahir',
			'email',
			'nip',
			'no_tlp',
			'user_name',
			'created_at',
			'updated_at'
    ];
}
