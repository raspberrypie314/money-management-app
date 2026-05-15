<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'name', 'type', 'category_type', 'is_primary_income',
        'value', 'period', 'icon', 'user_id'
    ];
    
    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    
    // Hitung nilai bersih per bulan
    public function getMonthlyValueAttribute()
    {
        if ($this->period == 'weekly') {
            return $this->value * 4;
        } elseif ($this->period == 'yearly') {
            return $this->value / 12;
        }
        return $this->value; // monthly or default
    }
}