@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Adicionar Tarefa</div>

                <div class="card-body">
                    <form action="{{ route('tarefa.store') }}" method='POST'>
                        @csrf
                        <div class="mb-3">
                          <label for="exampleInputEmail1" class="form-label">Tarefa</label>
                          <input type="text" class="form-control" name="tarefa">
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Data limite conclusão</label>
                          <input type="date" class="form-control" name="data_limite_conclusao">
                        </div>
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
