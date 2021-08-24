<?php

namespace App\Http\Controllers;

use App\Mail\AtualizadaTarefaMail;
use App\Mail\NovaTarefaMail;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TarefasExport;


class TarefaController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $tarefas = Tarefa::where('user_id', '=', $user_id)->paginate(2);
        return view('tarefa.index', ['tarefas' => $tarefas]);
        
        // $id = Auth::user()->id;
        // $nome = Auth::user()->name;
        // $email = Auth::user()->email;


        // if(Auth::check()) {
        //     $id = Auth::user()->id;
        //     $nome = Auth::user()->name;
        //     $email = Auth::user()->email;

        //     return "ID: $id | Nome: $nome | Email: $email";
        // } else {
        //     return 'Você não está logado no sistema';
        // }

        // if(Auth::check()) {
        //     $id = Auth::user()->id;
        //     $nome = Auth::user()->name;
        //     $email = Auth::user()->email;

        //     return "ID: $id | Nome: $nome | Email: $email";
        // } else {
        //     return 'Você não está logado no sistema';
        // }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tarefa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // no método all() pode ser passado os parametros desejados da Request;
        $dados = $request->all('tarefa', 'data_limite_conclusao');
        $dados['user_id'] = auth()->user()->id;
        $tarefa = Tarefa::create($dados);
        $destinatario = auth()->user()->email; 
        Mail::to($destinatario)->send(new NovaTarefaMail($tarefa));
        return redirect()->route('tarefa.show', ['tarefa' => $tarefa->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function show(Tarefa $tarefa)
    {
        return view('tarefa.show', ['tarefa' => $tarefa]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function edit(Tarefa $tarefa)
    {
        $user_id = auth()->user()->id;
        $tarefa->user_id;
        
        if($tarefa->user_id == $user_id) {
            return view('tarefa.edit', ['tarefa' => $tarefa]);
        } else {
            return view('acesso-negado');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tarefa $tarefa)
    {   
        $user_id = auth()->user()->id;
        if($tarefa->user_id == $user_id) {
            return view('acesso-negado');       
        } 

        $dados_antigos = [
            'tarefa' => $tarefa->getOriginal()['tarefa'],
            'data_limite_conclusao' => $tarefa->getOriginal()['data_limite_conclusao']
        ];

        $tarefa->update($request->all());

        $dados_novos = [
            'tarefa' => $tarefa['tarefa'],
            'data_limite_conclusao' => $tarefa['data_limite_conclusao']
        ];

        $id_tarefa = $tarefa['id'];

        $destinatario = auth()->user()->email;

        Mail::to($destinatario)->send(new AtualizadaTarefaMail($dados_antigos, $dados_novos, $id_tarefa));
        
        return redirect()->route('tarefa.show', ['tarefa' => $tarefa->id]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tarefa $tarefa)
    {
        $user_id = auth()->user()->id;

        if(!$tarefa->user_id == $user_id) {
            return view('acesso-negado');
        }

        $tarefa->delete();
        return redirect()->route('tarefa.index');
    }

    public function exportacao($extensao) {
        $nome_arquivo = 'lista-de-tarefas';
        
        if($extensao == 'xlsx') {
            $nome_arquivo .= '.'.$extensao;
        } else if($extensao == 'csv') {
            $nome_arquivo .= '.'.$extensao;
        } else {
            return redirect()->route('tarefa.index');
        }

        return Excel::download(new TarefasExport, $nome_arquivo);
    }
}
