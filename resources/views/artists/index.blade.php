@extends('layouts.app')

@section('title', 'Artist')

@section('content')
<div class="page-wrap">
    <section class="artist-collection-banner">
        <span class="eyebrow">The people behind the art</span>
        <h1>Artist</h1>
        <p>We're an online creative community where you can discover, support and be inspired by your new favourite artists.</p>
    </section>
    <section class="section">
        <div class="section-heading"><div><span class="eyebrow">Artists to discover</span><h2>Meet the artists</h2></div><span class="muted small">Find your new favourite creative</span></div>
        <div class="artist-grid">
            @foreach ($artists as $artist)
                <a class="artist-card" href="{{ route('artists.show', $artist) }}">
                    <div class="artist-card-avatar">{{ strtoupper(substr($artist->name, 0, 1)) }}</div>
                    <strong>{{ $artist->name }}</strong>
                    <span>Digital artist · {{ $artist->published_artworks_count }} artworks</span>
                    <span class="plain-link">View profile →</span>
                </a>
            @endforeach
        </div>
        {{ $artists->links() }}
    </section>
</div>
@endsection
