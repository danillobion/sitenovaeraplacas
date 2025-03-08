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

    protected $appends = [
        'cnpj_formatado',
        'telefone_formatado',
    ]; 
    
    public function endereco(){
        return $this->belongsTo(Endereco::class);
    }

    // Mutator para formatar o CNPJ
    public function getCnpjFormatadoAttribute()
    {
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "$1.$2.$3/$4-$5", $this->cnpj);
    }

    // Mutator para formatar o telefone
    public function getTelefoneFormatadoAttribute()
    {
        $telefone_limpo = str_replace(['(', ')', '-', ' '], '', $this->telefone);
        return preg_replace("/(\d{2})(\d{5})(\d{4})/", "($1) $2-$3", $telefone_limpo);
    }
}
