@extends('layout.base')

@section('title', 'Nyitólap')

@section('main-content')
<div class="container">
    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1>Üdvözlünk a blogon!</h1>
            <h3 class="mb-1">Minden bejegyzés</h3>
        </div>
        <div class="col-12 col-md-4">
            <div class="py-md-3 text-md-right">
                <p class="my-1">Elérhető műveletek:</p>
                <a href="{{ route('new-post') }}" role="button" class="btn btn-sm btn-success mb-1"><i class="fas fa-plus-circle"></i> Új bejegyzés</a>
                <a href="{{ route('new-category') }}" role="button" class="btn btn-sm btn-success mb-1"><i class="fas fa-plus-circle"></i> Új kategória</a>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-lg-9">
            <div class="row">

                @foreach ($posts as $post)
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-2">
                                    <h5 class="card-title mb-0">{{ $post->title }}</h5>
                                    <small class="text-secondary">
                                        <span class="mr-2">
                                            <i class="fas fa-user"></i>
                                            <span>{{ $post->author->name }}</span>
                                        </span>
                                        <span class="mr-2">
                                            <i class="far fa-calendar-alt"></i>
                                            <span>{{ $post->created_at }}</span>
                                        </span>
                                    </small>
                                </div>
                                <p class="card-text">{{ Str::of($post->text)->limit(32) }}</p>
                                <a href="{{ route('post', ['postId' => $post->id]) }}" class="btn btn-primary">Megtekint <i class="fas fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>     
                @endforeach
                
            </div>

            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {!! $posts->links() !!}
                </div>
            </div>

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
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Keresés</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title mb-2">Kategóriák</h5>
                            <p class="small">Bejegyzések megtekintése egy adott kategóriához.</p>
                            @foreach ($categories as $category)
                                <a href="#" class="badge badge-{{ $category->color }}">{{ $category->name }}</a>
                            @endforeach
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
                                    <li><span class="fa-li"><i class="fas fa-user"></i></span>Felhasználók: {{ $users_count }}</li>
                                    <li><span class="fa-li"><i class="fas fa-file-alt"></i></span>Bejegyzések: {{ $posts_count }}
                                    </li>
                                    {{-- <li><span class="fa-li"><i class="fas fa-comments"></i></span>Hozzászólások: 3 --}}
                                    </li>
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
