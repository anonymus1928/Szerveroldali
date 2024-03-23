@extends('layouts.layout')

@section('title', $ticket->title)

@section('content')
    <div class="flex mx-3">
        <div class="grow">
            <h1 class="text-4xl">
                {{ $ticket->title }}
                @switch($ticket->priority)
                    @case(0)
                        <span class="badge badge-info h-full badge-lg text-white font-bold text-2xl">Alacsony</span>
                        @break
                    @case(1)
                        <span class="badge badge-success h-full badge-lg text-white font-bold text-2xl">Normál</span>
                        @break
                    @case(2)
                        <span class="badge badge-warning h-full badge-lg text-white font-bold text-2xl">Magas</span>
                        @break
                    @case(3)
                        <span class="badge badge-error h-full badge-lg text-white font-bold text-2xl">Azonnal</span>
                        @break

                @endswitch
            </h1>
        </div>
        <div class="tooltip" data-tip="Szerkesztés">
            <button class="btn btn-outline mx-1">
                <i class="fa-solid fa-pen-to-square fa-fw fa-xl"></i>
            </button>
        </div>
        <div class="tooltip" data-tip="Felhasználók">
            <button class="btn btn-outline mx-1">
                <i class="fa-solid fa-users fa-fw fa-xl"></i>
            </button>
        </div>
        <div class="tooltip" data-tip="Lezárás">
            <button class="btn btn-outline btn-info mx-1">
                <i class="fa-solid fa-check fa-fw fa-xl"></i>
            </button>
        </div>
        <div class="tooltip" data-tip="Törlés">
            <button class="btn btn-outline btn-error mx-1">
                <i class="fa-solid fa-trash fa-fw fa-xl"></i>
            </button>
        </div>
    </div>
    <div class="divider"></div>

    @foreach ($ticket->comments as $comment)
        <div class="card shadow-xl">
            <div class="card-body">
                <h2 class="card-title flex">
                    <div class="grow">
                        <div class="badge badge-neutral">#{{ $loop->index }}</div>
                        | <strong>{{ $comment->user->name }}</strong> | {{ $comment->created_at }}
                    </div>
                    @isset($comment->filename)
                        <div>
                            <a href="#"><i class="fa-solid fa-download"></i></a>
                        </div>
                    @endif
                </h2>
                <p>{{ $comment->text }}</p>
            </div>
        </div>
    @endforeach
    <div class="divider"></div>
    <h2 class="text-3xl">Új hozzászólás írása</h2>
    <form action="" class="mt-3">
        <textarea
            placeholder="Hozzászólás..."
            class="textarea textarea-bordered textarea-md w-full max-w mb-3"
        ></textarea>
        <input type="file" class="file-input file-input-bordered w-full max-w" />
        <button class="btn btn-block btn-primary mt-3">Küldés</button>
    </form>
@endsection
