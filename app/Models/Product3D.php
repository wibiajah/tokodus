<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product3D extends Model
{
    protected $table = 'products_3d';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'width',
        'height',
        'depth',
        'material',
        'default_color',
        'price',
        'description_3d',
        'min_order',
        'is_active',
    ];

    protected $casts = [
        'width' => 'float',
        'height' => 'float',
        'depth' => 'float',
        'price' => 'float',
        'is_active' => 'boolean',
    ];

    public function getDimensions()
    {
        return [
            'width' => $this->width,
            'height' => $this->height,
            'depth' => $this->depth,
        ];
    }
}