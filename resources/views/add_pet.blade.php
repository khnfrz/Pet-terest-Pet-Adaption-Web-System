@extends('layouts.app')

@section('title', 'Add Pet - Petty')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/add.css') }}">
@endsection

@section('content')
<div class="container">
    <section>
        <form method="POST" action="{{ route('pet.store') }}" enctype="multipart/form-data">
            @csrf
            <h2>Add a New Pet</h2>

            @if(session('success'))
                <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <label for="petName">Name</label>
            <input
                id="petName"
                name="name"
                type="text"
                placeholder="Pet Name"
                value="{{ old('name') }}"
                required
            />

            <label for="petAge">Age</label>
            <input
                id="petAge"
                name="age"
                type="number"
                placeholder="Pet Age"
                min="0"
                value="{{ old('age') }}"
                required
            />

            <p>Species</p>
            <div class="speciesOption">
                <input
                    type="radio"
                    id="dog"
                    name="species"
                    value="Dog"
                    {{ old('species') == 'Dog' ? 'checked' : '' }}
                    hidden
                    required
                />
                <label for="dog" class="species-btn">Dog</label>

                <input 
                    type="radio" 
                    id="cat" 
                    name="species" 
                    value="Cat"
                    {{ old('species') == 'Cat' ? 'checked' : '' }}
                    hidden 
                />
                <label for="cat" class="species-btn">Cat</label>

                <input
                    type="radio"
                    id="pokemon"
                    name="species"
                    value="Pokemon"
                    {{ old('species') == 'Pokemon' ? 'checked' : '' }}
                    hidden
                />
                <label for="pokemon" class="species-btn">Pokemon</label>
            </div>

            <label for="petImage">Upload Image</label>
            <input
                id="petImage"
                name="image"
                type="file"
                accept="image/*"
                required
            />
            
            <button type="submit" class="submit-btn">Add Pet</button>
            <button type="button" class="page-btn" onclick="window.location='{{ route('home') }}'">Return to Page</button>
        </form>
    </section>
</div>
@endsection