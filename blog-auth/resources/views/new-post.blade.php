@extends('layouts.base')

@section('title', 'Új poszt')

@php
    // $categories = [
    //     [
    //         'id' => 0,
    //         'title' => 'Kategória 0',
    //         'color' => 'primary'
    //     ],
    //     [
    //         'id' => 1,
    //         'title' => 'Kategória 1',
    //         'color' => 'secondary'
    //     ],
    //     [
    //         'id' => 2,
    //         'title' => 'Kategória 2',
    //         'color' => 'danger'
    //     ],
    //     [
    //         'id' => 3,
    //         'title' => 'Kategória 3',
    //         'color' => 'warning'
    //     ],
    // ];
@endphp

@section('main-content')
<div class="container">
    <h1>Új bejegyzés</h1>
    <p class="mb-1">Ezen az oldalon tudsz új bejegyzést létrehozni.</p>
    <div class="mb-4">
        <a href="{{ route('home') }}"><i class="fas fa-long-arrow-alt-left"></i> Vissza a bejegyzésekhez</a>
    </div>

    @if (Session::has('post-added'))
        <div class="alert alert-success" role="alert">
            A(z) <strong>{{ Session::get('post-added') }}</strong> című bejegyzés sikeresen hozzá lett adva!
        </div>
    @endif

    @if (Session::has('post-edited'))
        <div class="alert alert-success" role="alert">
            A(z) <strong>{{ Session::get('post-edited') }}</strong> című bejegyzés frissítése sikeres!
        </div>
    @endif

    <form method="POST" action="{{ isset($post) ? route('store-edited-post', ['postId' => $post->id]) : route('store-new-post') }}" enctype="multipart/form-data">
        @csrf
        @if (isset($post))
            @method('put')
        @endif
        <div class="form-group row">
            <label for="title" class="col-sm-2 col-form-label">Cím*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Bejegyzés címe" value="{{ old('title', $post->title ?? '') }}">
                @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword" class="col-sm-2 col-form-label">Szöveg*</label>
            <div class="col-sm-10">
                <textarea rows="5" class="form-control @error('text') is-invalid @enderror" name="text" placeholder="Bejegyzés szövege">{{ old('text', $post->text ?? '') }}</textarea>
                @error('text')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword" class="col-sm-2 col-form-label">Kategória</label>
            <div class="col-sm-10">

                @forelse ($categories as $category)
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="{{ $category->id }}" id="{{ $loop->iteration }}" name="categories[]" @if(is_array(old('categories')) && in_array($category->id, old('categories')) || isset($post) && (in_array($category->id, $post->categories->pluck('id')->toArray()))) checked @endif>
                        <label for="{{ $loop->iteration }}" class="form-check-label">
                            <span class="badge badge-{{ $category->color }}">{{ $category->name }}</span>
                        </label>
                    </div>
                @empty
                    <p>Nincs megjeleníthető kategória.</p>
                @endforelse

                @error('categories.*')
                    <div class="small text-danger">
                        {{ $message }}
                    </div>
                @enderror

            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Beállítások</label>
            <div class="col-sm-10">
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="1" id="disable-comments" name="disable-comments" {{ old('disable-comments') || (isset($post) && $post->disabled_comments) ? 'checked' : ''}}>
                        <label for="disable-comments" class="form-check-label">Hozzászólások tiltása</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="1" id="hide-post" name="hide-post" {{ old('hide-post') || (isset($post) && $post->hide_post) ? 'checked' : ''}}>
                        <label for="hide-post" class="form-check-label">Bejegyzés elrejtése</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="attachment" class="col-sm-2 col-form-label">Csatolmány</label>
            <div class="col-sm-10">
                <div class="form-group">
                    <input type="file" class="form-control-file @error('attachment') is-invalid @enderror" id="attachment" name="attachment">
                    @error('attachment')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Létrehoz</button>
        </div>
    </form>
</div>
@endsection