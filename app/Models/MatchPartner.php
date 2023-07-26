<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MatchPartner extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'partner_id',
        'match_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id', 'id');
    }

}
