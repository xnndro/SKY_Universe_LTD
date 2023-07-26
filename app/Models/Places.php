<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Orders;

class Places extends Model
{
    use HasFactory;

    protected $table = 'places';
    protected $fillable = [
        'name',
        'address',
        'location',
        'price',
        'picture',
    ];

    public function orders()
    {
        return $this->hasMany(Orders::class, 'place_id', 'id');
    }
}
