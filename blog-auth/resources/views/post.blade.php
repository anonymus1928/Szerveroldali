@extends('layouts.base')

@section('title', 'Blog | ' . $post->title)

@section('main-content')
<div class="container">
    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1>{{ $post->title }}</h1>

            <div class="d-flex my-1 text-secondary">
                <span class="mr-2">
                    <i class="fas fa-user"></i>
                    <span>{{ $post->author->name }}</span>
                </span>
                <span class="mr-2">
                    <i class="far fa-calendar-alt"></i>
                    <span>{{ $post->create_at }}</span>
                </span>
            </div>

            <div class="mb-2">
                
                @foreach ($post->categories as $category)
                    <a href="#" class="badge badge-{{ $category->color }}">{{ $category->name }}</a>
                @endforeach

            </div>

            <div class="mb-3">
                <a href="{{ route('home') }}"><i class="fas fa-long-arrow-alt-left"></i> Minden bejegyzés</a>
            </div>
        </div>
        @if(Auth::id() === $post->author->id || Auth::user()->is_admin)
            <div class="col-12 col-md-4">
                <div class="py-md-3 text-md-right">
                    <p class="my-1">Bejegyzés kezelése:</p>
                    <a href="{{ route('edit-post', ['postId' => $post->id]) }}" type="button" class="btn btn-sm btn-primary text-light"><i class="far fa-edit"></i> Módosítás</a>
                    <form action="{{ route('delete-post', ['postId' => $post->id]) }}" method="post">
                        @csrf
                        @method('delete')
                        <button onclick="event.preventDefault(); this.closest('form').submit();" type="button" class="btn btn-sm btn-danger"><i class="far fa-trash-alt"></i> Törlés</button>
                    </form>


                </div>
            </div>
        @endif
    </div>

    <div class="mt-3">
        <p>{{ $post->text }}</p>

        @isset($url)
            <div class="attachment mb-3">
                <h5>Csatolmány</h5>
                <a href="{{ $url }}" target="_blank">{{ $post->attachment_original_name }}</a>
            </div>
        @endisset

        <h3>Hozzászólások</h3>

        @forelse ($post->comments as $comment)
            <div class="media mb-4">
                <div class="rounded-circle avatar mr-3">
                    <img src={{ asset('images/avatar.png') }} class="img-fluid" alt="Profilkép">
                </div>
                <div class="media-body">
                    <h5 class="my-0">{{ $comment->user->name }}</h5>
                    <p class="mb-2 text-secondary">
                        <small>
                            <i class="far fa-calendar-alt"></i>
                            <span>{{ $comment->created_at }}</span>
                        </small>
                    </p>
                    {{ $comment->text }}
                </div>
            </div>
        @empty
            Még nem érkezett hozzászólás.
        @endforelse

        @auth
            <h4>Hozzászólás írása</h4>
            <form>
                <div class="form-group">
                    <textarea rows="5" maxlength="1000" class="form-control" placeholder="Ide írhatsz hozzászólást" id="comment"></textarea>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="1" id="tag1" name="tags[]">
                        <label for="tag1" class="form-check-label">Elolvastam és elfogadom a <a href="rules.html">hozzászólási etikettet</a>.</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Hozzászólok</button>
            </form>
        @endauth
    </div>
</div>
@endsection
