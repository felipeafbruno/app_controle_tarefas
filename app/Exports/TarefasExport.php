<?php

namespace App\Exports;

use App\Models\Tarefa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; // Permite implementar títulos nas colunas do arquivo
use Maatwebsite\Excel\Concerns\WithMapping;

class TarefasExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return auth()->user()->tarefas()->get();
    }

    public function headings():array {
        return [
            'ID da Tarefa', 
            'ID do Usuário', 
            'Data limite conclusão', 
        ];
    }

    public function map($linha):array {
        // Com o método map() da interface WithMapping
        // é possível aplicar tratamentos/lógicas específicas para as
        // linhas de cada registro do excel ou qualquer outro tipo de arquivo implmentado
        return [
            $linha->id,
            $linha->tarefa,
            date('d/m/Y', strtotime($linha->data_limite_conclusao)),
        ];
    }
}
