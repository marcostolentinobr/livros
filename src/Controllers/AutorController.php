<?php

namespace App\Controllers;

/** Controller para gerenciar autores */
class AutorController extends BaseController
{
    protected string $pluralName = 'autores';

    /** Prepara e valida dados do formulário */
    protected function prepareData(): array
    {
        return $this->validateFields([
            ['nome', 'Nome', true]
        ]);
    }
}
