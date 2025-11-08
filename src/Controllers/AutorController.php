<?php

namespace App\Controllers;

/** Controller para gerenciar autores */
class AutorController extends BaseController
{
    protected $pluralName = 'autores';
    protected $icon = 'bi-person';

    /** Retorna definição dos campos do formulário */
    protected function getFields(): array
    {
        return [
            ['nome', 'Nome', true, 40]
        ];
    }
}
