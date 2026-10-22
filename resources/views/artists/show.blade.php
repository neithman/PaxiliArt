@extends('layouts.app')

@section('title', $artist->name)

@section('content')
<div class="page-wrap">
    <section class="artist-hero">
        <div class="artist-avatar-large">{{ strtoupper(substr($artist->name, 0, 1)) }}</div>
        <div class="artist-info">
            <span class="eyebrow">Independent artist</span>
            <h1>{{ $artist->name }}</h1>
            <p>{{ $artist->bio ?: 'Making little things for a more colorful world. Discover original work from this PaxiliArt artist.' }}</p>
            <span class="muted small">{{ $artworks->total() }} available artworks</span>
        </div>
        @auth
            @if (auth()->id() === $artist->id)
                <a class="button button-outline button-small" href="{{ route('seller.dashboard') }}">Your studio</a>
            @endif
        @endauth
    </section>
    <section class="section">
        <div class="section-heading"><div><span class="eyebrow">A peek inside their sketchbook</span><h2>Artwork by {{ $artist->name }}</h2></div></div>
        @if ($artworks->isNotEmpty())
            <div class="art-grid">@foreach ($artworks as $artwork) @include('components.artwork-card', ['artwork' => $artwork]) @endforeach</div>
            {{ $artworks->links() }}
        @else
            <div class="empty-state"><h3>No published artworks yet</h3><a class="plain-link" href="{{ route('home') }}">Explore the market</a></div>
        @endif
    </section>
</div>
@endsection
