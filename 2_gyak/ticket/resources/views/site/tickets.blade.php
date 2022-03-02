@extends('layouts.layout')

@section('title', 'Feladatok')

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
                    <tr class="@if($ticket->priority == 3) table-danger @elseif($ticket->priority == 2) table-warning @endif">
                        <td>
                            @switch($ticket->priority)
                                @case(0)
                                    <span class="badge rounded-pill bg-info fs-6">
                                        Alacsony
                                    </span>
                                    @break

                                @case(1)
                                    <span class="badge rounded-pill bg-success fs-6">
                                        Normál
                                    </span>
                                    @break

                                @case(2)
                                    <span class="badge rounded-pill bg-warning fs-6">
                                        Magas
                                    </span>
                                    @break

                                @case(3)
                                    <span class="badge rounded-pill bg-danger fs-6">
                                        Azonnal
                                    </span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <div>{{ $ticket->submitter->first()->name }}</div>
                            <div class="text-secondary">{{ $ticket->created_at }}</div>
                        </td>
                        <td>
                            <div>{{ $ticket->comments->sortByDesc('created_at')->first()->user->name }}</div>
                            <div class="text-secondary">{{ $ticket->comments->sortByDesc('created_at')->first()->created_at }}</div>
                        </td>
                        <td>
                            <div>
                                <a href="feladat.html">{{ $ticket->title }}</a>
                            </div>
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-info text-dark fs-6">
                                @if ($ticket->done)
                                    Lezárt
                                @elseif ($ticket->comments->count() == 1)
                                    Új
                                @elseif ($ticket->comments->count() > 1)
                                    Folyamatban
                                @endif
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-outline-secondary">
                                <i class="fa-solid fa-angles-right fa-fw"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
