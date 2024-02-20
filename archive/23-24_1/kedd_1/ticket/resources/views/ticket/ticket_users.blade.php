@extends('layouts.layout')

@section('title', $ticket->title . ' | Felhasználók')

@section('content')
    <div class="d-flex">
        <h1 class="ps-3 me-auto">{{ $ticket->title }}
            @switch($ticket->priority)
                @case(0)
                    <span class="badge bg-info">Alacsony</span></h1>
                    @break
                @case(1)
                    <span class="badge bg-success">Normál</span></h1>
                    @break
                @case(2)
                    <span class="badge bg-warning">Magas</span></h1>
                    @break
                @case(3)
                    <span class="badge bg-danger">Azonnal</span></h1>
                    @break
            @endswitch
        </h1>
        <button class="btn btn-outline-secondary mx-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Visszalépés">
            <i class="fa-solid fa-xmark fa-fw fa-xl"></i>
        </button>
    </div>
    <hr />
    <div class="row gx-5">
        <div class="col">
            <h2>Felhasználók</h2>
            <ul class="list-group">
                @foreach ($usersNotOnTicket as $user)
                    <li class="list-group-item d-flex align-items-center">
                        <div class="me-auto">
                            {{ $user->name }} |
                            <span class="text-secondary">{{ $user->email }}</span>
                        </div>

                        <div class="form-check form-switch pe-3">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                role="switch"
                                id="felhasznalo-2"
                            />
                        </div>
                        <button class="btn btn-primary">
                            <i class="fa-solid fa-angles-right fa-fw"></i>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col">
            <h2>A feladathoz hozzárendelt felhasználók</h2>
            <ul class="list-group">
                @foreach ($usersOnTicket as $user)
                    <li class="list-group-item d-flex align-items-center">
                        <div class="me-auto">
                            {{ $user->name }} |
                            <span class="text-secondary">{{ $user->email }}</span>
                        </div>

                        <div class="form-check form-switch pe-3">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                role="switch"
                                id="felhasznalo-2"
                                @checked($user->pivot->is_responsible)
                            />
                        </div>
                        <button class="btn btn-primary">
                            <i class="fa-solid fa-angles-right fa-fw"></i>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection

