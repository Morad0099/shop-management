<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category',
        'description',
        'price',
        'stock',
        'image',
        'size', 
    ];

    /**
     * Additional casts to define attribute types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
    ];

    /**
     * Accessor to get the full image URL.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return config('app.url') . '/storage/' . $this->image;
        }
        return null; // Default fallback if no image
    }


    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }
}
