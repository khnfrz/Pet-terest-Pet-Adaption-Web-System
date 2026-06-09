@extends('layouts.app')

@section('title', 'Home - Petty')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
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
    <section class="PETSsECTION">
        <div class="section-title">
            <h1>Pick Your Pawfect Friend</h1>
        </div>
        <br>
        <section class="photo-grid fade-in fade-in-delay-1">
            <div class="grid" id="grid"></div>
        </section>

        <!-- IMAGE MODAL -->
        <div class="modal" id="modal">
            <button class="close-btn" id="close-btn">&times;</button>
            <button class="nav-btn prev-btn" id="prev-btn">&#10094;</button>
            <div class="modal-content">
                <img id="modal-img" src="" alt="Large preview" />
            </div>
            <button class="nav-btn next-btn" id="next-btn">&#10095;</button>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    const petsData = @json($pets);

    // --- CALLING SEARCH HTML ---
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

    // --- SEARCH ITEM CLICK ---
    searchDropdown.addEventListener('click', e => {
        const item = e.target.closest('.search-item');
        if (item) {
            window.location.href = '/' + item.dataset.page + '?highlight=' + item.dataset.id;
        }
    });

    // --- HIDE DROPDOWN ON OUTSIDE CLICK ---
    document.addEventListener('click', e => {
        if (!searchDropdown.contains(e.target) && e.target !== searchInput) {
            searchDropdown.style.display = 'none';
        }
    });

    // --- ENTER KEY SELECTS FIRST RESULT ---
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

    // --- MASONRY GRID AND MODAL ---
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById("modal");
        const modalImg = document.getElementById("modal-img");
        const closeBtn = document.getElementById("close-btn");
        const prevBtn = document.getElementById("prev-btn");
        const nextBtn = document.getElementById("next-btn");
        let currentIndex = 0;

        const images = petsData.map(pet => "{{ asset('uploads') }}/" + pet.image);

        function shuffle(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        }

        const shuffled = shuffle([...images]);
        const grid = document.getElementById("grid");

        shuffled.forEach(src => {
            const div = document.createElement("div");
            div.classList.add("grid-item");
            div.innerHTML = `<img src="${src}" alt="Photo">`;
            grid.appendChild(div);
        });

        grid.addEventListener("click", e => {
            if (e.target.tagName === "IMG") {
                const index = Array.from(grid.querySelectorAll("img")).indexOf(e.target);

                e.target.scrollIntoView({
                    behavior: "smooth",
                    block: "center",
                    inline: "center"
                });

                setTimeout(() => {
                    openModal(index);
                }, 300);
            }
        });

        function openModal(index) {
            currentIndex = index;
            modalImg.src = shuffled[currentIndex];
            modal.style.display = "flex";
        }

        function showNext() {
            currentIndex = (currentIndex + 1) % shuffled.length;
            modalImg.src = shuffled[currentIndex];
        }

        function showPrev() {
            currentIndex = (currentIndex - 1 + shuffled.length) % shuffled.length;
            modalImg.src = shuffled[currentIndex];
        }

        closeBtn.addEventListener("click", () => modal.style.display = "none");
        nextBtn.addEventListener("click", showNext);
        prevBtn.addEventListener("click", showPrev);
        
        window.addEventListener("click", e => {
            if (e.target === modal) modal.style.display = "none";
        });

        window.addEventListener("keydown", e => {
            if (modal.style.display === "flex") {
                if (e.key === "ArrowRight") showNext();
                if (e.key === "ArrowLeft") showPrev();
                if (e.key === "Escape") modal.style.display = "none";
            }
        });
    });
</script>
@endsection