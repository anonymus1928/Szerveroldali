@extends('layouts.layout')

@section('title', '[Feladat neve]')

@section('content')
    <div class="d-flex">
        <h1 class="ps-3 me-auto">
            [Feladat tárgya] <span class="badge bg-danger">Azonnal</span>
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
                <li class="list-group-item d-flex align-items-center">
                    <div class="me-auto">
                        Felhasználó 2 |
                        <span class="text-secondary">felhasznalo2@szerveroldali.hu</span>
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
                <li class="list-group-item d-flex align-items-center">
                    <div class="me-auto">
                        Felhasználó 4 |
                        <span class="text-secondary">felhasznalo4@szerveroldali.hu</span>
                    </div>

                    <div class="form-check form-switch pe-3">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            role="switch"
                            id="felhasznalo-4"
                        />
                    </div>
                    <button class="btn btn-primary">
                        <i class="fa-solid fa-angles-right fa-fw"></i>
                    </button>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <div class="me-auto">
                        Felhasználó 6 |
                        <span class="text-secondary">felhasznalo6@szerveroldali.hu</span>
                    </div>

                    <div class="form-check form-switch pe-3">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            role="switch"
                            id="felhasznalo-6"
                        />
                    </div>
                    <button class="btn btn-primary">
                        <i class="fa-solid fa-angles-right fa-fw"></i>
                    </button>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <div class="me-auto">
                        Felhasználó 7 |
                        <span class="text-secondary">felhasznalo7@szerveroldali.hu</span>
                    </div>
                    <div class="form-check form-switch pe-3">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            role="switch"
                            id="felhasznalo-7"
                        />
                    </div>
                    <button class="btn btn-primary">
                        <i class="fa-solid fa-angles-right fa-fw"></i>
                    </button>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <div class="me-auto">
                        Felhasználó 8 |
                        <span class="text-secondary">felhasznalo8@szerveroldali.hu</span>
                    </div>

                    <div class="form-check form-switch pe-3">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            role="switch"
                            id="felhasznalo-8"
                        />
                    </div>
                    <button class="btn btn-primary">
                        <i class="fa-solid fa-angles-right fa-fw"></i>
                    </button>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <div class="me-auto">
                        Felhasználó 9 |
                        <span class="text-secondary">felhasznalo9@szerveroldali.hu</span>
                    </div>

                    <div class="form-check form-switch pe-3">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            role="switch"
                            id="felhasznalo-9"
                        />
                    </div>
                    <button class="btn btn-primary">
                        <i class="fa-solid fa-angles-right fa-fw"></i>
                    </button>
                </li>
            </ul>
        </div>
        <div class="col">
            <h2>A feladathoz hozzárendelt felhasználók</h2>
            <ul class="list-group">
                <li class="list-group-item d-flex align-items-center">
                    <div class="me-auto">
                        Felhasználó 1 |
                        <span class="text-secondary">felhasznalo1@szerveroldali.hu</span>
                    </div>
                    <div class="form-check form-switch pe-3">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            role="switch"
                            id="felhasznalo-1"
                        />
                    </div>
                    <button class="btn btn-primary">
                        <i class="fa-solid fa-angles-left fa-fw"></i>
                    </button>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <div class="me-auto">
                        Felhasználó 3 |
                        <span class="text-secondary">felhasznalo1@szerveroldali.hu</span>
                    </div>
                    <div class="form-check form-switch pe-3">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            role="switch"
                            id="felhasznalo-1"
                        />
                    </div>
                    <button class="btn btn-primary">
                        <i class="fa-solid fa-angles-left fa-fw"></i>
                    </button>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <div class="me-auto">
                        Felhasználó 5 |
                        <span class="text-secondary">felhasznalo1@szerveroldali.hu</span>
                    </div>
                    <div class="form-check form-switch pe-3">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            role="switch"
                            id="felhasznalo-1"
                        />
                    </div>
                    <button class="btn btn-primary">
                        <i class="fa-solid fa-angles-left fa-fw"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
@endsection
