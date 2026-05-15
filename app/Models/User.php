<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = ['username', 'password', 'name'];
    
    protected $hidden = ['password'];
    
    // Relasi
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}