@extends('layouts.layout')

@section('title', 'Tickets')

@section('content')
<div class="flex mx-3">
    <div class="grow">
        <h1 class="text-4xl">@if($closed) Lezárt @else Nyitott @endif feladatok</h1>
    </div>
</div>
<div class="divider"></div>

<div class="overflow-x-auto h-96">
    <table class="table table-pin-rows">
        <thead class="text-center">
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
                <tr class="@if($ticket->priority == 2) bg-amber-200 @elseif($ticket->priority == 3) bg-red-200 @endif">
                    <td>
                        @switch($ticket->priority)
                            @case(0)
                                <span class="badge badge-info h-full badge-lg text-white font-bold">Alacsony</span>
                                @break
                            @case(1)
                                <span class="badge badge-success h-full badge-lg text-white font-bold">Normál</span>
                                @break
                            @case(2)
                                <span class="badge badge-warning h-full badge-lg font-bold">Magas</span>
                                @break
                            @case(3)
                                <span class="badge badge-error h-full badge-lg text-white font-bold">Azonnal</span>
                                @break
                        @endswitch
                    </td>
                    <td>
                        <div>{{ $ticket->owner->first()->name }}</div>
                        <div class="text-gray-500">{{ $ticket->created_at }}</div>
                    </td>
                    <td>
                        <div>{{ $ticket->comments->sortByDesc('created_at')->first()->user->name }}</div>
                        <div class="text-gray-500">{{ $ticket->comments->sortByDesc('created_at')->first()->created_at }}</div>
                    </td>
                    <td>
                        <div>{{ $ticket->title }}</div>
                    </td>
                    <td>
                        <span class="badge rounded-pill bg-info text-dark fs-6">
                            @if($ticket->done)
                                Lezárva
                            @elseif($ticket->comments->count() == 1)
                                Új
                            @else
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
