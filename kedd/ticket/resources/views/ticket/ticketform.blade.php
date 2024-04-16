@extends('layouts.layout')

@isset($ticket)
    @section('title', $ticket->title . " módosítás")
@else
    @section('title', 'Új feladat')
@endisset

@section('content')
    <div class="flex mx-3">
        <div class="grow">
            @isset($ticket)
                <h1 class="text-4xl">{{ $ticket->title }} módosítás</h1>
            @else
                <h1 class="text-4xl">Új feladat</h1>
            @endisset
        </div>
    </div>
    <div class="divider"></div>
    <form method="POST" action="@isset($ticket) {{route('tickets.update', ['ticket' => $ticket->id])}} @else {{route('tickets.store')}} @endisset" enctype="multipart/form-data">
        @csrf
        @isset($ticket)
            @method('PUT')
        @endisset
        <div class="grid grid-cols-2 gap-5">
            <div class="w-full">
                <input
                    type="text"
                    placeholder="Tárgy"
                    class="input input-bordered w-full @error('title') input-error @enderror"
                    name="title"
                    id="title"
                    value="{{ old('title', $ticket->title ?? '') }}"
                />
                @error('title')
                    <div class="label">
                        <span class="label-text-alt text-red-600">{{ $message }}</span>
                    </div>
                @enderror
            </div>
            <div class="">
                <select
                    class="select select-bordered w-full basis-1/2 @error('priority') select-error @enderror"
                    name="priority"
                    id="priority"
                >
                    <option disabled>Priorítás</option>
                    {{--
                    <option value="0" {{ old('priority') == 0 ? 'selected' : '' }}>Alacsony</option>
                    <option value="1" {{ old('priority') == 1 ? 'selected' : '' }}>Normál</option>
                    <option value="2" {{ old('priority') == 2 ? 'selected' : '' }}>Magas</option>
                    <option value="3" {{ old('priority') == 3 ? 'selected' : '' }}>Azonnal</option>
                    --}}
                    <option value="0" @selected(old('priority', $ticket->priority ?? '') == 0)>Alacsony</option>
                    <option value="1" @selected(old('priority', $ticket->priority ?? '') == 1)>Normál</option>
                    <option value="2" @selected(old('priority', $ticket->priority ?? '') == 2)>Magas</option>
                    <option value="3" @selected(old('priority', $ticket->priority ?? '') == 3)>Azonnal</option>
                </select>
                @error('priority')
                    <div class="label">
                        <span class="label-text-alt text-red-600">{{ $message }}</span>
                    </div>
                @enderror
            </div>
        </div>
        @empty($ticket)
            <div class="py-5 w-full">
                <textarea
                    class="textarea textarea-bordered w-full @error('text') textarea-error @enderror"
                    placeholder="Hiba leírása..."
                    name="text"
                    id="text"
                    cols="30"
                    rows="5"
                >{{ old('text') }}</textarea>
                @error('text')
                    <div class="label">
                        <span class="label-text-alt text-red-600">{{ $message }}</span>
                    </div>
                @enderror
            </div>
            <div class="pb-5">
                <input
                    type="file"
                    class="file-input file-input-bordered w-full @error('file') file-input-error @enderror"
                    name="file"
                    id="file"
                />
                @error('file')
                    <div class="label">
                        <span class="label-text-alt text-red-600">{{ $message }}</span>
                    </div>
                @enderror
            </div>
        @endempty
        <button type="submit" class="btn btn-primary w-full mt-5">Mentés</button>
    </form>
@endsection
