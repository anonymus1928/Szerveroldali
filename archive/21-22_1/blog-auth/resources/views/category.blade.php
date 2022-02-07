@extends('layouts.base')

@section('title', $categoryId)

@section('main-content')
<div class="container">
    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1>Üdvözlünk a blogon!</h1>
            <h3 class="mb-1"><span class="badge badge-info">Kategória</span> bejegyzései</h3>
            <div class="mb-1">
                <a href="index.html"><i class="fas fa-long-arrow-alt-left"></i> Minden bejegyzés</a>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="py-md-3 text-md-right">
                <p class="my-1">Kategória kezelése:</p>
                <a href="#" role="button" class="btn btn-sm btn-primary"><i class="far fa-edit"></i> Módosítás</a>
                <button type="button" class="btn btn-sm btn-danger"><i class="far fa-trash-alt"></i> Törlés</button>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-lg-9">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="card-title mb-0">Bejegyzés címe</h5>
                                <small class="text-secondary">
                                    <span class="mr-2">
                                        <i class="fas fa-user"></i>
                                        <span>Dávid</span>
                                    </span>
                                    <span class="mr-2">
                                        <i class="far fa-calendar-alt"></i>
                                        <span>2021. 02. 10.</span>
                                    </span>
                                </small>
                            </div>
                            <p class="card-text">A bejegyzés szövegének az eleje...</p>
                            <a href="post.html" class="btn btn-primary">Megtekint <i class="fas fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="card-title mb-0">Bejegyzés címe</h5>
                                <small class="text-secondary">
                                    <span class="mr-2">
                                        <i class="fas fa-user"></i>
                                        <span>Dávid</span>
                                    </span>
                                    <span class="mr-2">
                                        <i class="far fa-calendar-alt"></i>
                                        <span>2021. 02. 10.</span>
                                    </span>
                                </small>
                            </div>
                            <p class="card-text">A bejegyzés szövegének az eleje...</p>
                            <a href="post.html" class="btn btn-primary">Megtekint <i class="fas fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="card-title mb-0">Bejegyzés címe</h5>
                                <small class="text-secondary">
                                    <span class="mr-2">
                                        <i class="fas fa-user"></i>
                                        <span>Dávid</span>
                                    </span>
                                    <span class="mr-2">
                                        <i class="far fa-calendar-alt"></i>
                                        <span>2021. 02. 10.</span>
                                    </span>
                                </small>
                            </div>
                            <p class="card-text">A bejegyzés szövegének az eleje...</p>
                            <a href="post.html" class="btn btn-primary">Megtekint <i class="fas fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="card-title mb-0">Bejegyzés címe</h5>
                                <small class="text-secondary">
                                    <span class="mr-2">
                                        <i class="fas fa-user"></i>
                                        <span>Dávid</span>
                                    </span>
                                    <span class="mr-2">
                                        <i class="far fa-calendar-alt"></i>
                                        <span>2021. 02. 10.</span>
                                    </span>
                                </small>
                            </div>
                            <p class="card-text">A bejegyzés szövegének az eleje...</p>
                            <a href="post.html" class="btn btn-primary">Megtekint <i class="fas fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="card-title mb-0">Bejegyzés címe valami hosszabb</h5>
                                <small class="text-secondary">
                                    <span class="mr-2">
                                        <i class="fas fa-user"></i>
                                        <span>Dávid</span>
                                    </span>
                                    <span class="mr-2">
                                        <i class="far fa-calendar-alt"></i>
                                        <span>2021. 02. 10.</span>
                                    </span>
                                </small>
                            </div>
                            <p class="card-text">A bejegyzés szövegének az eleje...</p>
                            <a href="post.html" class="btn btn-primary">Megtekint <i class="fas fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Előző</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Következő</a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="col-12 col-lg-3">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title mb-2">Keresés</h5>
                            <p class="small">Bejegyzés keresése cím alapján.</p>
                            <form>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Keresett cím">
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i>
                                    Keresés</button>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title mb-2">Kategóriák</h5>
                            <p class="small">Bejegyzések megtekintése egy adott kategóriához.</p>
                            <a href="#" class="badge badge-primary">Primary</a>
                            <a href="#" class="badge badge-secondary">Secondary</a>
                            <a href="#" class="badge badge-success">Success</a>
                            <a href="#" class="badge badge-danger">Danger</a>
                            <a href="#" class="badge badge-warning">Warning</a>
                            <a href="#" class="badge badge-info">Info</a>
                            <a href="#" class="badge badge-light">Light</a>
                            <a href="#" class="badge badge-dark">Dark</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title mb-2">Statisztika</h5>
                            <div class="small">
                                <p class="mb-1">Adatbázis statisztika:</p>
                                <ul class="fa-ul">
                                    <li><span class="fa-li"><i class="fas fa-user"></i></span>Felhasználók: 1</li>
                                    <li><span class="fa-li"><i class="fas fa-file-alt"></i></span>Bejegyzések: 1</li>
                                    <li><span class="fa-li"><i class="fas fa-comments"></i></span>Hozzászólások: 3</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

