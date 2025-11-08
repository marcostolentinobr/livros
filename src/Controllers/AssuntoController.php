<?php

namespace App\Controllers;

/** Controller para gerenciar assuntos */
class AssuntoController extends BaseController
{
    protected $icon = 'bi-tag';
    protected $showPrimaryKey = true;

    /** Retorna definição dos campos do formulário */
    protected function getFields(): array
    {
        return [
            ['descricao', 'Descrição', true, 20]
        ];
    }

    
}
