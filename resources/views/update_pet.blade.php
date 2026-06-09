@extends('layouts.app')

@section('title', 'Update Pet - Petty')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/update.css') }}">
@endsection

@section('content')
<div class="container">
    <section>
        <form method="POST" action="{{ route('pet.update', $pet->id) }}" enctype="multipart/form-data">
            @csrf
            <h2>Update Pet Info</h2>

            @if(session('success'))
                <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    {{ session('success') }}
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
                value="{{ old('name', $pet->name) }}" 
                required 
            />

            <label for="petAge">Age</label>
            <input 
                id="petAge" 
                name="age" 
                type="number" 
                value="{{ old('age', $pet->age) }}" 
                min="0" 
                required 
            />

            <p>Species</p>
            <div class="speciesOption">
                <input 
                    type="radio" 
                    id="dog" 
                    name="species" 
                    value="Dog" 
                    {{ old('species', $pet->species) === 'Dog' ? 'checked' : '' }} 
                    hidden 
                />
                <label for="dog" class="species-btn">Dog</label>

                <input 
                    type="radio" 
                    id="cat" 
                    name="species" 
                    value="Cat" 
                    {{ old('species', $pet->species) === 'Cat' ? 'checked' : '' }} 
                    hidden 
                />
                <label for="cat" class="species-btn">Cat</label>

                <input 
                    type="radio" 
                    id="pokemon" 
                    name="species" 
                    value="Pokemon" 
                    {{ old('species', $pet->species) === 'Pokemon' ? 'checked' : '' }} 
                    hidden 
                />
                <label for="pokemon" class="species-btn">Pokemon</label>
            </div>

            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="Available" {{ old('status', $pet->status) === 'Available' ? 'selected' : '' }}>Available</option>
                <option value="Adopted" {{ old('status', $pet->status) === 'Adopted' ? 'selected' : '' }}>Adopted</option>
            </select>

            <label for="petImage">Upload New Image (Optional)</label>
            <input id="petImage" name="image" type="file" accept="image/*" />
            
            @if($pet->image)
                <img src="{{ asset('uploads/' . $pet->image) }}" alt="Pet Image" width="150" style="margin-top:10px;border-radius:8px;">
            @endif

            <button type="submit" class="submit-btn">Save Changes</button>
            <button type="button" class="page-btn" onclick="window.location='{{ route('home') }}'">Cancel</button>
        </form>
    </section>
</div>
@endsection