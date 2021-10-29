@extends('layouts.base')

@section('title', 'Új kategória')

@section('main-content')
<div class="container">
    <h1>Új kategória</h1>
    <p class="mb-1">Ezen az oldalon tudsz új kategóriát létrehozni. A bejegyzéseket úgy tudod hozzárendelni, ha a
        kategória létrehozása után módosítod a bejegyzést, és ott bejelölöd ezt a kategóriát is.</p>
    <div class="mb-4">
        <a href="{{ route('home') }}"><i class="fas fa-long-arrow-alt-left"></i> Vissza a bejegyzésekhez</a>
    </div>

    @if (Session::has('category-added'))
        <div class="alert alert-success" role="alert">
            A(z) <strong>{{ Session::get('category-added') }}</strong> kategória sikeresen hozzá lett adva!
        </div>
    @endif

    <form method="post">
        @csrf
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Név*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Kategória neve" value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="color" class="col-sm-2 col-form-label">Szín*</label>
            <div class="col-sm-10">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="color" id="color-primary" value="primary" @if(!is_null(old('color')) && old('color') === 'primary') checked @endif)>
                    <label class="form-check-label" for="color-primary">
                        <span class="badge badge-primary">Primary</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="color" id="color-secondary" value="secondary" @if(!is_null(old('color')) && old('color') === 'secondary') checked @endif)>
                    <label class="form-check-label" for="color-secondary">
                        <span class="badge badge-secondary">Secondary</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="color" id="color-success" value="success" @if(!is_null(old('color')) && old('color') === 'success') checked @endif)>
                    <label class="form-check-label" for="color-success">
                        <span class="badge badge-success">Success</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="color" id="color-danger" value="danger" @if(!is_null(old('color')) && old('color') === 'danger') checked @endif)>
                    <label class="form-check-label" for="color-danger">
                        <span class="badge badge-danger">Danger</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="color" id="color-warning" value="warning" @if(!is_null(old('color')) && old('color') === 'warning') checked @endif)>
                    <label class="form-check-label" for="color-warning">
                        <span class="badge badge-warning">Warning</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="color" id="color-info" value="info" @if(!is_null(old('color')) && old('color') === 'info') checked @endif)>
                    <label class="form-check-label" for="color-info">
                        <span class="badge badge-info">Info</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="color" id="color-light" value="light" @if(!is_null(old('color')) && old('color') === 'light') checked @endif)>
                    <label class="form-check-label" for="color-light">
                        <span class="badge badge-light">Light</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="color" id="color-dark" value="dark" @if(!is_null(old('color')) && old('color') === 'dark') checked @endif)>
                    <label class="form-check-label" for="color-dark">
                        <span class="badge badge-dark">Dark</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Létrehoz</button>
        </div>
    </form>
</div>
@endsection
