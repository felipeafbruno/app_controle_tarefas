@component('mail::message')
Tarefa {{ $dados_antigos['tarefa'] }} foi atualizada.

Dados anteriores: 
    <p style="margin-left:10px">Descrição: {{ $dados_antigos['tarefa'] }}</p>
    <p style="margin-left:10px">Data limite de conclusão {{ $dados_antigos['data_limite_conclusao'] }}</p>

Atualização:
    <p style="margin-left:10px">Descrição: {{ $dados_novos['tarefa'] }}</p>
    <p style="margin-left:10px">Data limite de conclusão {{ $dados_novos['data_limite_conclusao'] }}</p>    

@component('mail::button', ['url' => 'http://localhost:8000/tarefa/'.$id_tarefa])
Clique aqui para visualizar
@endcomponent

Att,<br>
{{ config('app.name') }}
@endcomponent
