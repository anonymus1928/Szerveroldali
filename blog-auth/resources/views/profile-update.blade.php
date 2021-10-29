@extends('layouts.base')

@section('title', 'Szabályok')

@section('main-content')
<div class="container">
    <h1>Profil frissítése</h1>
    <p class="mb-1">Ezen az oldalon tudod frissíteni a profil adataidat.</p>
    <div class="mb-4">
        <a href="profile.html"><i class="fas fa-long-arrow-alt-left"></i> Vissza a profilhoz</a>
    </div>

    <form>
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Név*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" placeholder="Neved">
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">E-mail*</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="email" name="email" placeholder="E-mail címed">
            </div>
        </div>
        <div class="form-group row">
            <label for="avatar" class="col-sm-2 col-form-label">Profilkép</label>
            <div class="col-sm-10">
                <div class="form-group">
                    <input type="file" class="form-control-file" id="avatar" name="avatar">
                </div>

                <div>
                    <p>Jelenlegi kép:</p>
                    <img src="images/avatar.png" class="img-fluid avatar-preview" alt="Profilkép">

                    <div class="form-check mt-3">
                        <input type="checkbox" class="form-check-input" value="1" id="tag1" name="tags[]">
                        <label for="tag1" class="form-check-label">Profilkép törlése</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="current-password" class="col-sm-2 col-form-label">Új jelszó</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="new-password" name="new-password" placeholder="Új jelszó beállítása">
                <small id="new-password-help" class="form-text text-muted">Csak akkor töltsd ki, ha jelszót szeretnél változtatni.</small>
            </div>
        </div>

        <div class="form-group row">
            <label for="current-password" class="col-sm-2 col-form-label">Új jelszó megerősítése</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="new-password-confirm" name="new-password-confirm" placeholder="Új jelszó megerősítése">
                <small id="new-password-confirm-help" class="form-text text-muted">Csak akkor töltsd ki, ha jelszót szeretnél változtatni.</small>
            </div>
        </div>

        <div class="form-group row">
            <label for="current-password" class="col-sm-2 col-form-label">Jelenlegi jelszó*</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="current-password" name="current-password" placeholder="Jelenlegi jelszavad">
                <small id="current-password-help" class="form-text text-muted">A módosítások mentéséhez szükséges a jelenlegi jelszó megadása.</small>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Mentés</button>
        </div>
    </form>
</div>
@endsection
