<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyAutoTransaction extends Model
{
    protected $table = 'monthly_auto_transactions';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'user_id', 'category_id', 'month', 'year', 
        'is_processed', 'processed_at'
    ];
    
    protected $casts = [
        'is_processed' => 'boolean',
        'processed_at' => 'datetime'
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