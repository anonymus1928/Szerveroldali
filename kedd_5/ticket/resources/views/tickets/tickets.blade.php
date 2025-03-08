@extends('tickets.layout')

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
                    <tr class="table-danger">
                        <td>
                            <span class="badge rounded-pill bg-danger fs-6">Azonnal</span>
                        </td>
                        <td>
                            <div>Teszt Felhasználó</div>
                            <div class="text-secondary">2022. 02. 11. 10:48</div>
                        </td>
                        <td>
                            <div>Teszt Felhasználó</div>
                            <div class="text-secondary">2022. 02. 11. 11:32</div>
                        </td>
                        <td>
                            <div>
                                <a href="feladat.html">{{ $ticket->title }}</a>
                            </div>
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-info text-dark fs-6">Új</span>
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

