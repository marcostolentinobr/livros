<?php

namespace App\Controllers;

/** Controller para gerenciar assuntos */
class AssuntoController extends BaseController
{
    /** Prepara e valida dados do formulário */
    protected function prepareData(): array
    {
        return $this->validateFields([
            ['descricao', 'Descrição', true]
        ]);
    }
}
