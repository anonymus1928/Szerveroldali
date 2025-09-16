@extends('tickets.layout')

@section('title', $ticket->title)

@section('content')
    <div class="d-flex">
        <h1 class="ps-3 me-auto">{{ $ticket->title }}
            @switch($ticket->priority)
                @case(3)
                    <span class="badge bg-danger">Azonnal</span>
                    @break

                @case(2)
                    <span class="badge bg-warning text-white">Magas</span>
                    @break

                @case(1)
                    <span class="badge bg-success">Normál</span>
                    @break

                @case(0)
                    <span class="badge bg-info">Alacsony</span>
                    @break

            @endswitch
        </h1>
        <a href="{{ route('tickets.edit', ['ticket' => $ticket->id]) }}" class="btn btn-primary mx-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Szerkesztés">
            <i class="fa-solid fa-pen-to-square fa-fw fa-xl"></i>
        </a>
        <button class="btn btn-primary mx-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Felhasználók">
            <i class="fa-solid fa-users fa-fw fa-xl"></i>
        </button>
        <button class="btn btn-success mx-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lezárás">
            <i class="fa-solid fa-check fa-fw fa-xl"></i>
        </button>
        <form action="{{ route('tickets.destroy', ['ticket' => $ticket->id]) }}" method="post">
            @csrf
            @method('delete')
            <button class="btn btn-danger mx-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Törlés">
                <i class="fa-solid fa-trash fa-fw fa-xl"></i>
            </button>
        </form>
    </div>
    <hr />
    @foreach ($ticket->comments()->orderBy('created_at', 'asc')->get() as $comment)
        <div class="card mb-3">
            <div class="card-header d-flex">
                <div class="me-auto"><span class="badge bg-secondary">#{{ $loop->index }}</span> | <strong>{{ $comment->user->name }}</strong> | {{ $comment->created_at }}</div>
                @if ($comment->filename)
                    <div><a download="{{ $comment->filename }}" href="{{ Storage::url($comment->filename_hash) }}"><i class="fa-solid fa-download"></i></a></div>
                @endif
            </div>
            <div class="card-body">
                <pre>{{ $comment->text }}</pre>
            </div>
        </div>
    @endforeach
    <hr>
    <h2>Új hozzászólás írása</h2>
    <form>
        <div class="mb-3">
            <textarea class="form-control" name="text" id="text" cols="30" rows="10" placeholder="Hozzászólás..."></textarea>
        </div>
        <div class="mb-3">
            <input type="file" class="form-control" id="file">
        </div>
        <div class="row">
            <button type="submit" class="btn btn-primary">Küldés</button>
        </div>
    </form>
@endsection
