@extends('layout.base')

@section('title', 'Poszt')

@section('main-content')
<div class="container">
    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1>Bejegyzés címe</h1>

            <div class="d-flex my-1 text-secondary">
                <span class="mr-2">
                    <i class="fas fa-user"></i>
                    <span>Dávid</span>
                </span>
                <span class="mr-2">
                    <i class="far fa-calendar-alt"></i>
                    <span>2021. 02. 10.</span>
                </span>
            </div>

            <div class="mb-2">
                <a href="#" class="badge badge-primary">Primary</a>
                <a href="#" class="badge badge-secondary">Secondary</a>
                <a href="#" class="badge badge-success">Success</a>
                <a href="#" class="badge badge-danger">Danger</a>
                <a href="#" class="badge badge-warning">Warning</a>
                <a href="#" class="badge badge-info">Info</a>
                <a href="#" class="badge badge-light">Light</a>
                <a href="#" class="badge badge-dark">Dark</a>
            </div>

            <div class="mb-3">
                <a href="index.html"><i class="fas fa-long-arrow-alt-left"></i> Minden bejegyzés</a>
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
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
            dolore magna aliqua. Pharetra pharetra massa massa ultricies mi quis hendrerit dolor. Tristique
            sollicitudin nibh sit amet. Vivamus at augue eget arcu dictum varius duis at consectetur. Et odio
            pellentesque diam volutpat commodo. Et netus et malesuada fames ac. Elementum eu facilisis sed odio.
            Varius quam quisque id diam vel quam elementum. A diam sollicitudin tempor id eu. Lobortis scelerisque
            fermentum dui faucibus in ornare quam viverra. Turpis massa tincidunt dui ut ornare lectus sit amet.
            Varius vel pharetra vel turpis nunc eget. Diam maecenas ultricies mi eget mauris pharetra et ultrices
            neque. Augue eget arcu dictum varius duis at consectetur. Volutpat diam ut venenatis tellus in metus
            vulputate eu scelerisque. Ornare arcu odio ut sem nulla pharetra. Nulla aliquet porttitor lacus luctus
            accumsan tortor posuere. At quis risus sed vulputate odio ut enim. Nisl vel pretium lectus quam id. Ut
            porttitor leo a diam sollicitudin tempor. Aliquam purus sit amet luctus venenatis lectus. Amet nulla
            facilisi morbi tempus. Egestas dui id ornare arcu odio ut. Lobortis scelerisque fermentum dui faucibus
            in. Erat velit scelerisque in dictum non consectetur a erat nam. Aliquet bibendum enim facilisis gravida
            neque convallis. Ultricies lacus sed turpis tincidunt id aliquet risus feugiat. Lectus magna fringilla
            urna porttitor rhoncus dolor purus non. Aliquet nec ullamcorper sit amet risus nullam eget felis.</p>

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
