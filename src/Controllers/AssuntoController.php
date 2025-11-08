<?php

namespace App\Controllers;

/** Controller para gerenciar assuntos */
class AssuntoController extends BaseController
{
    protected string $icon = 'bi-tag';
    /** Retorna definição dos campos do formulário */
    protected function getFields(): array
    {
        return [
            ['descricao', 'Descrição', true, 20]
        ];
    }
}
