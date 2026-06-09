@extends('layouts.app')

@section('title', 'Dogs - Petty')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/pet.css') }}">
@endsection

@section('content')
<div class="container">
    @include('partials.header')

    <!-- WELCOME SECTION -->
    <section class="WELCOMEsECTION">
        <div class="descriptions">
            <h1>GET YOUR NEW FURRY FAMILY MEMBER TODAY</h1>
            <p>Where tails wag and hearts connect. Every paw deserves a loving home.</p>
        </div>
        <div class="pet">
            <img src="{{ asset('uploads/hi.png') }}" alt="Welcome Pet" />
        </div>
    </section>

    <!-- PETS SECTION -->
    <div class="petsSelection">
        <div class="photo-grid">
            <h2>Adoptable Dogs</h2>
            <p>Find your next best friend among our adorable pets!</p>
            @if($pets->isEmpty())
                <p style="color: red;">No Dogs found matching your search.</p>
            @endif
            <div class="grid"></div>
        </div>
    </div>
</div>

<!-- MODAL -->
<form method="POST">
    <div class="modal" id="petModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <img id="modalImg" src="" alt=""/>
            <h2 id="modalName"></h2>
            <p><strong>Species:</strong> <span id="modalSpecies"></span></p>
            <p><strong>Age:</strong> <span id="modalAge"></span></p>
            <button type="button" id="adoptBtn">Adopt</button>
            <br>
            <button type="button" id="editBtn">Edit</button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    const petsData = @json($pets);
    const pets = @json($pets);

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

    // --- PET GRID AND MODAL ---
    document.addEventListener("DOMContentLoaded", () => {
        const grid = document.querySelector(".grid");                   
        const modal = document.getElementById("petModal");
        const closeBtn = document.querySelector(".close");                  
        const adoptBtn = document.getElementById("adoptBtn");  
        const editBtn = document.getElementById("editBtn");
        
        const modalImg = document.getElementById("modalImg");
        const modalName = document.getElementById("modalName");
        const modalSpecies = document.getElementById("modalSpecies");
        const modalAge = document.getElementById("modalAge");         

        pets.sort(() => Math.random() - 0.5);

        pets.forEach((pet) => {
            const div = document.createElement("div");
            div.classList.add("grid-item");
            div.setAttribute("data-pet-id", pet.id);

            const img = document.createElement("img");
            img.src = "{{ asset('uploads') }}/" + pet.image;
            img.loading = "lazy";
            img.alt = pet.name;

            div.appendChild(img);
            grid.appendChild(div);

            div.addEventListener("click", () => {
                openPetModal(pet);
            });
        });

        function openPetModal(pet) {
            modal.style.display = "flex";
            modalImg.src = "{{ asset('uploads') }}/" + pet.image;
            modalName.textContent = pet.name;
            modalSpecies.textContent = pet.species;
            modalAge.textContent = pet.age;

            adoptBtn.onclick = () => {
                if (confirm(`Are you sure you want to adopt ${pet.name}?`)) {
                    window.location.href = "/delete-pet/" + pet.id;
                }
            };

            editBtn.onclick = () => {
                window.location.href = "/edit-pet/" + pet.id;
            };
        }

        const urlParams = new URLSearchParams(window.location.search);
        const highlightId = urlParams.get('highlight');

        if (highlightId) {
            const targetDiv = document.querySelector(`.grid-item[data-pet-id='${highlightId}']`);
            if (targetDiv) {
                targetDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                const petObj = pets.find(p => p.id == highlightId);
                if (petObj) {
                    setTimeout(() => openPetModal(petObj), 400);
                }
            }
        }
       
        closeBtn.addEventListener("click", () => {
            modal.style.display = "none";
        });
        
        window.addEventListener("click", (e) => {
            if (e.target === modal) modal.style.display = "none";
        });
    });
</script>
@endsection