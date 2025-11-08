<?php

namespace App\Models;

/**
 * Model para a entidade Autor
 * Gerencia operações CRUD relacionadas a autores
 */
class Autor extends Model
{
    protected string $table = 'Autor';
    protected string $primaryKey = 'CodAu';
}

