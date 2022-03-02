@extends('layouts.layout')

@section('title', 'Felhasználók')

@section('content')
    <h1 class="ps-3">Felhasználók</h1>
    <hr />
    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="text-center table-light">
                <tr>
                    <th style="width: 10%">#</th>
                    <th style="width: 30%">Név / Email-cím</th>
                    <th style="width: 20%">Regisztráció dátuma</th>
                    <th style="width: 15%">Nyitott feladatok száma</th>
                    <th style="width: 15%">Lezárt feladatok száma</th>
                    <th style="width: 10%">Admin</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <span class="badge bg-secondary fs-6">{{ $user->id }}</span>
                        </td>
                        <td>
                            <div>{{ $user->name }}</div>
                            <div class="text-secondary">{{ $user->email }}</div>
                        </td>
                        <td>
                            <div class="text-secondary">{{ $user->created_at }}</div>
                        </td>
                        <td>3</td>
                        <td>104</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-danger" @if($user->is_admin) disabled @endif>
                                    <i class="fa-solid fa-xmark fa-fw"></i>
                                </button>
                                <button class="btn btn-success" @if(!$user->is_admin) disabled @endif>
                                    <i class="fa-solid fa-check fa-fw"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
