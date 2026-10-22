@extends('layouts.app')

@section('title', $isMarketplace ? 'Marketplace' : 'Original digital art')

@section('content')
<div class="page-wrap">
    @if (!$isMarketplace && !$search && !$category)
        <section class="hero hero-home">
            <div class="hero-copy">
                <span class="hero-welcome">WELCOME TO</span>
                <img class="hero-wordmark" src="{{ asset('images/brand-logo.png') }}" alt="PaxiliArt">
                <p>Explore creative digital artworks made by talented artists.</p>
                <form class="hero-search" action="{{ route('marketplace.index') }}" method="GET">
                    <input type="search" name="q" placeholder="Search digital art, artists, collections..." aria-label="Search digital art">
                    <button type="submit" aria-label="Search">⌕</button>
                </form>
                <div class="hero-buttons">
                    <a class="button button-outline" href="{{ route('seller.dashboard') }}">Start Selling</a>
                    <a class="button" href="{{ route('marketplace.index') }}">Explore Marketplace</a>
                </div>
            </div>
        </section>

        <section class="section home-categories">
            <div class="section-heading"><div><h2>Browse by category</h2><span class="muted">Find art that matches your style</span></div><a class="plain-link" href="{{ route('marketplace.index') }}">View all →</a></div>
            @php($browseCategories = ['Anime' => 'portrait-orange.jpeg', 'Fantasy' => 'forest-dreamer.jpeg', 'Monsters' => 'cosmic-orbit.jpeg', 'Others' => 'portrait-gold.jpeg'])
            <div class="category-cards">
                @foreach ($browseCategories as $browseName => $browseImage)
                    <a class="category-card" href="{{ route('marketplace.index', ['category' => $browseName]) }}">
                        <img src="{{ asset('images/artwork/'.$browseImage) }}" alt="">
                        <strong>{{ $browseName }}</strong>
                    </a>
                @endforeach
            </div>
        </section>

        @if ($featured->isNotEmpty())
            <section class="section" id="discover">
                <div class="section-heading"><div><h2>Featured Artwork</h2><span class="muted">Handpicked artworks from our featured artists.</span></div><a class="plain-link" href="{{ route('marketplace.index') }}">View all →</a></div>
                <div class="art-grid">
                    @foreach ($featured as $artwork)
                        @include('components.artwork-card', ['artwork' => $artwork])
                    @endforeach
                </div>
            </section>
        @endif
    @else
        <section class="shop-banner">
            <span class="eyebrow">PaxiliArt Marketplace</span>
            <h1>{{ $category ?: ($search ? 'Search results' : 'Shop') }}</h1>
            <p>{{ $search ? 'Find something you love in the collection.' : 'Discover amazing digital artwork' }}</p>
        </section>
        <section class="section market-listing">
            <div class="market-controls">
                <form action="{{ route('marketplace.index') }}" method="GET">
                    @if ($search)<input type="hidden" name="q" value="{{ $search }}">@endif
                    <label class="sr-only" for="category">Category</label>
                    <select name="category" id="category" onchange="this.form.submit()">
                        <option value="">Category: All</option>
                        @foreach ($categories as $categoryName)
                            <option value="{{ $categoryName }}" @selected($category === $categoryName)>Category: {{ $categoryName }}</option>
                        @endforeach
                    </select>
                </form>
                <form action="{{ route('marketplace.index') }}" method="GET">
                    @if ($search)<input type="hidden" name="q" value="{{ $search }}">@endif
                    @if ($category)<input type="hidden" name="category" value="{{ $category }}">@endif
                    <label class="sr-only" for="sort">Sort by</label>
                    <select name="sort" id="sort" onchange="this.form.submit()">
                        <option value="newest" @selected($sort === 'newest')>Sort by: Newest</option>
                        <option value="featured" @selected($sort === 'featured')>Sort by: Featured</option>
                        <option value="popular" @selected($sort === 'popular')>Sort by: Popular</option>
                        <option value="price_low" @selected($sort === 'price_low')>Price: low to high</option>
                        <option value="price_high" @selected($sort === 'price_high')>Price: high to low</option>
                    </select>
                </form>
            </div>
            @if ($artworks->isNotEmpty())
                <div class="art-grid">
                    @foreach ($artworks as $artwork)
                        @include('components.artwork-card', ['artwork' => $artwork])
                    @endforeach
                </div>
                {{ $artworks->links() }}
            @else
                <div class="empty-state"><div class="empty-icon">✧</div><h3>No artwork found just yet</h3><p class="muted small">Try another search or explore the full collection.</p><a class="button button-outline button-small" href="{{ route('marketplace.index') }}">Clear filters</a></div>
            @endif
        </section>
    @endif
</div>
@endsection
