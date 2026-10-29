@extends('layouts.app')
@section('title', 'Nuevo producto')
@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h1 class="h5 mb-0">Nuevo producto</h1></div>
            <div class="card-body">
                <form method="POST" action="{{ route('productos.store') }}" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" class="form-control @error('nombre') is-invalid @enderror">
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <input type="text" id="categoria" name="categoria" value="{{ old('categoria') }}" class="form-control @error('categoria') is-invalid @enderror">
                        @error('categoria')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" id="precio" name="precio" value="{{ old('precio') }}" class="form-control @error('precio') is-invalid @enderror">
                            @error('precio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success"><i class="bi bi-save"></i> Guardar</button>
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection