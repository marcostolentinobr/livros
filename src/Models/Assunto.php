<?php

namespace App\Models;

/**
 * Model para a entidade Assunto
 * Gerencia operações CRUD relacionadas a assuntos
 */
class Assunto extends Model
{
    protected string $table = 'Assunto';
    protected string $primaryKey = 'codAs';
}
