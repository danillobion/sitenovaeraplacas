<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'id',
        'imagem',
        'nome',
        'descricao',
        'valor',
        'exibir_valor'
    ];
}
