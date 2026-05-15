<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    
    // 🔥 MATIKAN TIMESTAMPS
    public $timestamps = false;
    
    protected $fillable = [
        'user_id', 'category_id', 'amount', 'transaction_date',
        'month', 'year', 'description', 'type'
    ];
    
    protected $casts = [
        'transaction_date' => 'date'
    ];
    
    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}