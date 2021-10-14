@extends('layout.base')

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
                    <span>{{ $post->created_at }}</span>
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
        <div class="col-12 col-md-4">
            <div class="py-md-3 text-md-right">
                <p class="my-1">Bejegyzés kezelése:</p>
                <button type="button" class="btn btn-sm btn-primary"><i class="far fa-edit"></i> Módosítás</button>
                <button type="button" class="btn btn-sm btn-danger"><i class="far fa-trash-alt"></i> Törlés</button>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <p>{{ $post->text }}</p>

        <div class="attachment mb-3">
            <h5>Csatolmány</h5>
            <a href="#">csatolmany.pdf</a>
        </div>

        <h3>Hozzászólások</h3>
        <div class="media mb-4">
            <div class="rounded-circle avatar mr-3">
                <img src="images/avatar.png" class="img-fluid" alt="Profilkép">
            </div>
            <div class="media-body">
                <h5 class="my-0">Dávid</h5>
                <p class="mb-2 text-secondary">
                    <small>
                        <i class="far fa-calendar-alt"></i>
                        <span>2021. 02. 10.</span>
                    </small>
                </p>
                Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras
                purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi
                vulputate fringilla. Donec lacinia congue felis in faucibus.
            </div>
        </div>
        <div class="media mb-4">
            <div class="rounded-circle avatar mr-3">
                <img src="images/avatar.png" class="img-fluid" alt="Profilkép">
            </div>
            <div class="media-body">
                <h5 class="my-0">Dávid</h5>
                <p class="mb-2 text-secondary">
                    <small>
                        <i class="far fa-calendar-alt"></i>
                        <span>2021. 02. 10.</span>
                    </small>
                </p>
                Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras
                purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi
                vulputate fringilla. Donec lacinia congue felis in faucibus.
            </div>
        </div>

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
    </div>
</div>
@endsection
