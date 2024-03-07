<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penghuni extends Model
{
    use HasFactory;

    protected $table = 'penghuni';


    public function history_penghuni()
    {
        return $this->hasMany(HistoryPenghuni::class, 'penghuni_id', 'id');
    }
}
