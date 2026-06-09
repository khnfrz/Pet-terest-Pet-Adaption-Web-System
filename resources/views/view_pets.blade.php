@extends('layouts.app')

@section('title', 'All Pets - Petty')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/all.css') }}">
@endsection

@section('content')
<div class="container">
    @include('partials.header')

    <!-- MAIN CONTENT -->
    <section>
        <h1>🐾 All Pets Overview</h1>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 20px 0;">
                {{ session('success') }}
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pet Image</th>
                    <th>Name</th>
                    <th>Species</th>
                    <th>Age</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pets as $pet)
                    <tr>
                        <td>{{ $pet->id }}</td>
                        <td>
                            @if($pet->image)
                                <img src="{{ asset('uploads/' . $pet->image) }}" alt="{{ $pet->name }}">
                            @else
                                <span style="color:gray;">No Image</span>
                            @endif
                        </td>
                        <td>{{ $pet->name }}</td>
                        <td>{{ $pet->species }}</td>
                        <td>{{ $pet->age }}</td>
                        <td>{{ $pet->status }}</td>
                        <td class="actions">
                            <a href="{{ route('pet.edit', $pet->id) }}" class="btn btn-edit">Edit</a>
                            <a href="{{ route('pet.delete', $pet->id) }}" 
                               class="btn btn-delete" 
                               onclick="return confirm('Are you sure you want to delete {{ $pet->name }}?')">
                                Delete
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; color:gray;">No pets found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>
@endsection

@section('scripts')
<script>
    const petsData = @json($pets);

    // --- SEARCH FUNCTIONALITY ---
    const searchInput = document.getElementById('searchInput');
    const searchDropdown = document.getElementById('searchDropdown');

    searchInput.addEventListener('input', function() {
        const val = this.value.trim().toLowerCase();

        if (!val) {
            searchDropdown.style.display = 'none';
            searchDropdown.innerHTML = '';
            return;
        }

        const results = petsData.filter(pet =>
            pet.name.toLowerCase().includes(val) ||
            pet.species.toLowerCase().includes(val)
        ); 

        if (results.length === 0) {
            searchDropdown.innerHTML = '<div style="padding:8px;color:gray;">No results found</div>';
            searchDropdown.style.display = 'block';
            return;
        }

        searchDropdown.innerHTML = results.map(pet =>
            `<div class="search-item" 
                style="padding:8px;cursor:pointer;border-bottom:1px solid #eee;"
                data-page="${pet.species.toLowerCase()}" data-id="${pet.id}">
                <strong>${pet.name}</strong>
                <span style="color:#1ba750;"> (${pet.species})</span>
            </div>`
        ).join('');
        searchDropdown.style.display = 'block';
    });

    searchDropdown.addEventListener('click', e => {
        const item = e.target.closest('.search-item');
        if (item) {
            window.location.href = '/' + item.dataset.page + '?highlight=' + item.dataset.id;
        }
    });

    document.addEventListener('click', e => {
        if (!searchDropdown.contains(e.target) && e.target !== searchInput) {
            searchDropdown.style.display = 'none';
        }
    });

    searchInput.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            const first = searchDropdown.querySelector('.search-item');
            if (first) {
                window.location.href = '/' + first.dataset.page + '?highlight=' + first.dataset.id;
            }
        }
    });

    // --- FADE-IN EFFECT ---
    const faders = document.querySelectorAll(".fade-in");
    const options = { threshold: 0.3 };

    const appearOnScroll = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.style.animationPlayState = "running";
            observer.unobserve(entry.target);
        });
    }, options);

    faders.forEach(fader => {
        fader.style.animationPlayState = "paused";
        appearOnScroll.observe(fader);
    });
</script>
@endsection