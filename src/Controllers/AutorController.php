<?php

namespace App\Controllers;

/** Controller para gerenciar autores */
class AutorController extends BaseController
{
    protected string $pluralName = 'autores';
    protected string $icon = 'bi-person';

    /** Retorna definição dos campos do formulário */
    protected function getFields(): array
    {
        return [
            ['CodAu', 'Código', false, null, true],
            ['nome', 'Nome', true, 40]
        ];
    }
}
