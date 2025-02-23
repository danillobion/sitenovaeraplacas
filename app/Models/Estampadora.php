<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estampadora extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nome',
        'cnpj',
        'imagem',
        'telefone',
        'horario_funcionamento',
        'endereco_id',
    ];

    public function endereco(){
        return $this->belongsTo(Endereco::class);
    }
}
