<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Informativo extends Model
{
    protected $fillable = ['titulo', 'imagem', 'habilitado'];
}
