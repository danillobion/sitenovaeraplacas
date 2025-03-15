<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\TipoPermissao;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nome',
        'email',
        'password',
        'tipo_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }    

    public function tipo(){
        return $this->belongsTo(Tipo::class);
    }

    public function hasPermission(string $permission): bool {

        $tipo_permissao = TipoPermissao::with('permissoes')->where('tipo_id', $this->tipo_id)->get();
        $nomesPermissoes = $tipo_permissao->map(function ($item) {
            return $item['permissoes']['nome'] ?? null; 
        })->filter()->toArray();

        return in_array($permission, $nomesPermissoes);

    }

}
