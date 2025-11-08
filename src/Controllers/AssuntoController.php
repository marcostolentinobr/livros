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
            ['codAs', 'Código', false, null, true],
            ['descricao', 'Descrição', true, 20]
        ];
    }
}
