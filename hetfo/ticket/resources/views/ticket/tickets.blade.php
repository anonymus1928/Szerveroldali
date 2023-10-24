@extends('layouts.layout')

@section('title', 'Nyitott feladatok')

@section('content')
    <h1 class="ps-3">Feladatok</h1>
    <hr />
    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="text-center table-light">
                <tr>
                    <th style="width: 10%">Priorítás</th>
                    <th style="width: 15%">Beküldő</th>
                    <th style="width: 15%">Utolsó hozzászóló</th>
                    <th style="width: 40%">Tárgy</th>
                    <th style="width: 10%">Státusz</th>
                    <th style="width: 10%"></th>
                </tr>
            </thead>
            <tbody class="text-center">


                @foreach ($tickets as $ticket)
                    <tr class="@if ($ticket->priority == 2) table-warning @elseif ($ticket->priority == 3) table-danger @endif">
                        <td>
                            @switch($ticket->priority)
                                @case(0)
                                    <span class="badge rounded-pill bg-info text-dark fs-6">Alacsony</span>
                                    @break

                                @case(1)
                                    <span class="badge rounded-pill bg-success text-dark fs-6">Normál</span>
                                    @break

                                @case(2)
                                    <span class="badge rounded-pill bg-warning text-dark fs-6">Magas</span>
                                    @break

                                @case(3)
                                    <span class="badge rounded-pill bg-danger text-dark fs-6">Azonnal</span>
                                    @break

                            @endswitch
                        </td>
                        <td>
                            <div>{{ $ticket->author->first()->name }}</div>
                            <div class="text-secondary">{{ $ticket->created_at }}</div>
                        </td>
                        <td>
                            <div>{{ $ticket->comments->sortByDesc('created_at')->first()->user->name }}</div>
                            <div class="text-secondary">{{ $ticket->comments->sortByDesc('created_at')->first()->created_at }}</div>
                        </td>
                        <td>
                            <div>
                                <a href="{{ route('tickets.show', ['ticket' => $ticket->id]) }}">{{ $ticket->title }}</a>
                            </div>
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-info text-dark fs-6">
                                @if ($ticket->done)
                                    Lezárva
                                @elseif ($ticket->comments->count() == 1)
                                    Új
                                @else
                                    Folyamatban
                                @endif
                            </span>
                        </td>
                        <td>
                            <a class="btn btn-outline-secondary" href="{{ route('tickets.show', ['ticket' => $ticket->id]) }}">
                                <i class="fa-solid fa-angles-right fa-fw"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        {{ $tickets->onEachSide(5)->links() }}
    </div>
@endsection
