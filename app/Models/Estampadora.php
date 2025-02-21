<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Estampadora extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nome',
        'cnpj',
        'endereco_id',
    ];

    public function endereco(){
        return $this->belongsTo(Endereco::class);
    }
}
