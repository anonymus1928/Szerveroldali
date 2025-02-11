@extends('ticket.layout')

@section('title', isset($ticket) ? $ticket->title : 'Új feladat létrehozása')

@section('content')
    <h1 class="ps-3">{{ isset($ticket) ? $ticket->title : 'Új feladat'}}</h1>
    <hr />
    <form method="POST" enctype="multipart/form-data" action="{{ isset($ticket) ? route('tickets.update', ['ticket' => $ticket->id]) : route('tickets.store') }}">
        @csrf
        @isset($ticket)
            @method('patch')
        @endisset
        <div class="row mb-3">
            <div class="col">
                <input
                    type="text"
                    class="form-control @error('title') is-invalid @enderror"
                    placeholder="Tárgy"
                    name="title"
                    id="title"
                    value="{{ old('title', $ticket->title ?? '') }}"
                />
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <select class="form-select @error('priority') is-invalid @enderror" name="priority" id="priority">
                    <option value="x" disabled>Priorítás</option>
                    {{-- <option value="0" {{ old('priority') == 0 ? 'selected' : '' }}>Alacsony</option>
                    <option value="1" {{ old('priority') == 1 ? 'selected' : '' }}>Normál</option>
                    <option value="2" {{ old('priority') == 2 ? 'selected' : '' }}>Magas</option>
                    <option value="3" {{ old('priority') == 3 ? 'selected' : '' }}>Azonnal</option> --}}
                    <option value="0" @selected(old('priority', $ticket->priority ?? '') == 0)>Alacsony</option>
                    <option value="1" @selected(old('priority', $ticket->priority ?? '') == 1)>Normál</option>
                    <option value="2" @selected(old('priority', $ticket->priority ?? '') == 2)>Magas</option>
                    <option value="3" @selected(old('priority', $ticket->priority ?? '') == 3)>Azonnal</option>
                </select>
                @error('priority')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        @empty($ticket)
            <div class="mb-3">
                <textarea class="form-control @error('text') is-invalid @enderror" name="text" id="text" cols="30" rows="10" placeholder="Hiba leírása...">{{ old('text') }}</textarea>
                @error('text')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" id="file">
                @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        @endempty
        <div class="row">
            <button type="submit" class="btn btn-primary">Mentés</button>
        </div>
    </form>
@endsection
