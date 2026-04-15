<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Permissao;
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
        
    protected $appends = [
        'quantidade_total_requisicoes',
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

    public function isRoot(): bool
    {
        return $this->tipo()->where('nome', 'root')->exists();
    }

    public function permissionNames(): array
    {
        if ($this->isRoot()) {
            return Permissao::pluck('nome')->all();
        }

        $tipo_permissao = TipoPermissao::with('permissoes')
            ->where('tipo_id', $this->tipo_id)
            ->get();

        return $tipo_permissao->map(function ($item) {
            return $item['permissoes']['nome'] ?? null;
        })->filter()->values()->all();
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isRoot()) {
            return true;
        }

        return in_array($permission, $this->permissionNames(), true);

    }

    // Quantidade total de requisições
    public function getQuantidadeTotalRequisicoesAttribute(){
        $quantidade = LogConsulta::where('usuario_id', $this->id)->count();
        return $quantidade;
    }

}
