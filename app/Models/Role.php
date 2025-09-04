<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    //
    protected $table = 'roles';

    protected $fillable=['name','status'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
