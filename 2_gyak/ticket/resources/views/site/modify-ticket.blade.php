@extends('layouts.layout')

@section('title', 'Új feladat')

@section('content')
    <h1 class="ps-3">Új feladat</h1>
    <hr />
    <form>
        <div class="row mb-3">
            <div class="col">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Tárgy"
                    name="title"
                    id="title"
                />
            </div>
            <div class="col">
                <select class="form-select" name="priority" id="priority">
                    <option value="x" disabled>Priorítás</option>
                    <option value="0">Alacsony</option>
                    <option value="1">Normál</option>
                    <option value="2">Magas</option>
                    <option value="3">Azonnal</option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <textarea class="form-control" name="text" id="text" cols="30" rows="10" placeholder="Hiba leírása..."></textarea>
        </div>
        <div class="mb-3">
            <input type="file" class="form-control" id="file">
        </div>
        <div class="row">
            <button type="submit" class="btn btn-primary">Mentés</button>
        </div>
    </form>
@endsection
