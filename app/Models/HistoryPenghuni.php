<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPenghuni extends Model
{
    use HasFactory;

    protected $table = 'history_penghuni';
    protected $guarded = [];
    public function penghuni()
    {
        return $this->hasMany(Penghuni::class, 'id', 'penghuni_id');
    }

    public function rumah()
    {
        return $this->hasMany(Rumah::class, 'id', 'rumah_id');
    }
}
