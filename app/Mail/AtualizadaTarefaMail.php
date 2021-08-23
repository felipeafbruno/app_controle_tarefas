<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AtualizadaTarefaMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $dados_antigos; // descricao da tarefa e data_conclusao_limite 
    public $dados_novos; // descricao da tarefa e data_conclusao_limite 
    public $id_tarefa;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dados_antigos, $dados_novos, $id_tarefa)
    {
        $this->dados_antigos = $dados_antigos;
        $this->dados_novos = $dados_novos;
        $this->id_tarefa = $id_tarefa;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.atualizada-tarefa')->subject('Tarefa atualizada');
    }
}
