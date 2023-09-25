@extends('layouts.layout')

@isset($ticket)
    @section('title', $ticket->title)
@else
    @section('title', 'Új feladat')
@endisset

@section('content')
    @isset($ticket)
        <h1 class="ps-3">{{ $ticket->title }}</h1>
    @else
        <h1 class="ps-3">Új feladat</h1>
    @endisset
    <hr />
    <form method="post" @isset($ticket) action="{{ route('feladatok.update', ['feladatok' => $ticket->id]) }}" @else action="{{ route('feladatok.store') }}" @endisset enctype="multipart/form-data">
        @csrf
        @isset($ticket)
            @method('put')
        @endisset
        <div class="row mb-3">
            <div class="col">
                <input
                    type="text"
                    class="form-control @error('title') is-invalid @enderror"
                    placeholder="Tárgy"
                    name="title"
                    id="title"
                    value="{{ old('title', isset($ticket) ? $ticket->title : "") }}"
                />
                @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col">
                <select class="form-select @error('priority') is-invalid @enderror" name="priority" id="priority">
                    <option value="x" disabled>Priorítás</option>
                    <option value="0" @if(isset($ticket) && $ticket->priority === 0) selected @endif>Alacsony</option>
                    <option value="1" @if(isset($ticket) && $ticket->priority === 1) selected @endif>Normál</option>
                    <option value="2" @if(isset($ticket) && $ticket->priority === 2) selected @endif>Magas</option>
                    <option value="3" @if(isset($ticket) && $ticket->priority === 3) selected @endif>Azonnal</option>
                </select>
                @error('priority')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="mb-3">
            <textarea class="form-control @error('text') is-invalid @enderror" name="text" id="text" cols="30" rows="10" placeholder="Hiba leírása..." @isset($ticket) disabled @endisset>{{ old('text') }}</textarea>
            @error('text')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <input type="file" class="form-control" id="file" name="file" @isset($ticket) disabled @endisset>
        </div>
        <div class="row">
            <button type="submit" class="btn btn-primary">Mentés</button>
        </div>
    </form>
@endsection
