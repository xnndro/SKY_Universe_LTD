<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MatchPartner;
use App\Models\Places;

class Orders extends Model
{
    use HasFactory;
    protected $fillable = [
        'match_id',
        'place_id',
        'order_transaction_id',
        'payment_status',
        'total_price',
    ];

    public function match()
    {
        return $this->belongsTo(MatchPartner::class, 'match_id', 'id');
    }

    public function place()
    {
        return $this->belongsTo(Places::class, 'place_id', 'id');
    }
}
