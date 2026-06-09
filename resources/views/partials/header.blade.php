<header class="header">
    <div class="name">
        <img src="{{ asset('uploads/logo.png') }}" alt="Petty Logo" />
        <h1 class="title">PETTY</h1>
    </div>

    <form class="search" method="get" action="{{ route('pet.search') }}">
        <button type="submit">
            <img src="{{ asset('uploads/search.png') }}" alt="Search Icon">
        </button>
        <input 
            id="searchInput"
            type="text"
            name="search"
            placeholder="Search..."
            value="{{ $search ?? '' }}"
        />
        <div id="searchDropdown"></div>
    </form>

    <nav class="nav-links">
        <a href="{{ route('home') }}" class="{{ Request::routeIs('home') ? 'isHome' : '' }}">Home</a>
        <a href="{{ route('dog') }}" class="{{ Request::routeIs('dog') ? 'isHome' : '' }}">Dog</a>
        <a href="{{ route('cat') }}" class="{{ Request::routeIs('cat') ? 'isHome' : '' }}">Cat</a>
        <a href="{{ route('pokemon') }}" class="{{ Request::routeIs('pokemon') ? 'isHome' : '' }}">Pokemon</a>
        <a href="{{ route('view.all') }}" class="viewPet">All Pets</a>
        <a href="{{ route('pet.create') }}" class="addPet" style="color: white;">Add Pet</a>
    </nav>
</header>