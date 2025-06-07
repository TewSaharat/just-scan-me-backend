<?php

namespace App\Models;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject {
    protected $fillable = ['name', 'email', 'password', 'role','Category_code'];
    protected $hidden = ['password', 'remember_token','created_at', 'updated_at'];
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'Category_code' => $this->Category_code,
        ];
    }
}

