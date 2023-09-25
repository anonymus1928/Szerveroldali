@extends('layouts.layout')

@section('title', $ticket->title)

@section('content')
    <div class="d-flex">
        <h1 class="ps-3 me-auto">
            {{ $ticket->title }}
        </h1>
        <a class="btn btn-outline-secondary mx-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Visszalépés" href="{{ route('feladatok.show', ['feladatok' => $ticket->id]) }}">
            <i class="fa-solid fa-xmark fa-fw fa-xl"></i>
        </a>
    </div>
    <hr />
    <div class="row gx-5">
        <div class="col">
            <h2>Felhasználók</h2>
            <ul class="list-group">
                @foreach ($otherUsers as $user)
                    <li class="list-group-item d-flex align-items-center">
                        <div class="me-auto">
                            {{ $user->name }} |
                            <span class="text-secondary">{{ $user->email }}</span>
                        </div>

                        <form action="{{ route('feladatok.felhasznalok.add', ['feladatok' => $ticket->id, 'felhasznalo' => $user->id]) }}" method="post" class="d-flex align-items-center">
                            @csrf
                            <div class="form-check form-switch pe-3">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    role="switch"
                                    name="is_responsible"
                                    value="true"
                                />
                            </div>
                            <button class="btn btn-primary" type="submit">
                                <i class="fa-solid fa-angles-right fa-fw"></i>
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col">
            <h2>A feladathoz hozzárendelt felhasználók</h2>
            <ul class="list-group">
                @foreach ($ticket->users as $user)
                    <li class="list-group-item d-flex align-items-center">
                        <div class="me-auto">
                            {{ $user->name }} |
                            <span class="text-secondary">{{ $user->email }}</span>
                        </div>

                        <form action="{{ route('feladatok.felhasznalok.remove', ['feladatok' => $ticket->id, 'felhasznalo' => $user->id]) }}" method="post" class="d-flex align-items-center">
                            @csrf
                            @method('delete')
                            <div class="form-check form-switch pe-3">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    role="switch"
                                    name="is_responsible"
                                    value="true"
                                    @if($user->pivot->is_responsible) checked @endif
                                />
                            </div>
                            <button class="btn btn-primary" type="submit">
                                <i class="fa-solid fa-angles-left fa-fw"></i>
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
