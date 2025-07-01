<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;

    protected $fillable = [
        'house_number',
        'status',
    ];

    public function house_residents()
    {
        return $this->hasMany(HouseResident::class);
    }
}
