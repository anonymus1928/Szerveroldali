@extends('layout.base')

@section('title', 'Profil')

@section('main-content')
<div class="container">
    <h1 class="mb-4">Profil</h1>
    <div class="row mb-5">
        <div class="col-12 col-md-4 text-center">
            <img src="images/avatar.png" class="img-fluid rounded-circle mb-3" alt="Profilkép">
        </div>
        <div class="col-12 col-md-4">
            <h4>Dávid</h4>
            <p class="my-0">E-mail: totadavid95@inf.elte.hu</p>
            <p class="my-0">Regisztrált: 2021. 02. 10.</p>
            <p class="my-0">Bejegyzések: 2</p>
            <p class="my-0">Hozzászólások: 3</p>
            <a href="profile-update.html" role="button" class="btn btn-success mt-4"><i class="far fa-edit"></i> Adatok frissítése</a>
        </div>
    </div>

    <h3>Legutóbbi bejegyzések</h3>
</div>
@endsection

