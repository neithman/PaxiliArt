@extends('layouts.app')

@section('title', $artwork->title)

@section('content')
<div class="page-wrap">
    <div class="small muted" style="margin-top:25px"><a class="plain-link" href="{{ route('home') }}">Discover</a> &nbsp; / &nbsp; {{ $artwork->category }} &nbsp; / &nbsp; {{ $artwork->title }}</div>
    <section class="detail-grid">
        <div>
            <img class="detail-image" src="{{ $artwork->cover_url }}" alt="{{ $artwork->title }}">
            <div class="detail-thumbnails">
                <img src="{{ $artwork->cover_url }}" alt="">
                <img src="{{ $artwork->cover_url }}" alt="">
                <img src="{{ $artwork->cover_url }}" alt="">
            </div>
        </div>
        <div class="detail-copy">
            <span class="eyebrow">{{ $artwork->category }}</span>
            <h1>{{ $artwork->title }}</h1>
            <div class="detail-byline">✧ &nbsp;by <a class="plain-link" href="{{ route('artists.show', $artwork->artist) }}">{{ $artwork->artist->name }}</a> &nbsp; · &nbsp; ♡ {{ $artwork->sales_count }} collectors</div>
            <div class="detail-price">₱{{ number_format((float) $artwork->price, 2) }}</div>
            <p class="detail-description">{{ $artwork->description }}</p>
            <div class="detail-spec"><span>▧ &nbsp; File format &amp; resolution</span><strong>{{ $artwork->dimensions ?: 'High-resolution digital download' }}</strong></div>
            <div class="detail-spec"><span>♙ &nbsp; License type</span><strong>Personal use license</strong></div>
            <div class="quantity-line"><span>Quantity</span><span class="quantity-control"><button type="button" disabled>−</button><strong>1</strong><button type="button" disabled>＋</button></span></div>
            @auth
                @if ($artwork->artist_id !== auth()->id())
                    <form class="detail-actions" method="POST" action="{{ route('cart.store', $artwork) }}">
                        @csrf
                        <button class="button button-outline" type="submit">Add to Cart</button>
                        <button class="button" type="submit" name="buy_now" value="1">Buy Now</button>
                    </form>
                @else
                    <a class="button button-outline" href="{{ route('seller.artworks.edit', $artwork) }}">Manage artwork</a>
                @endif
            @else
                <div class="detail-actions"><a class="button button-outline" href="{{ route('login') }}">Add to Cart</a><a class="button" href="{{ route('login') }}">Buy Now</a></div>
            @endauth
            <div class="detail-meta"><span>✧ Digital download</span><span>♙ Personal use</span><span>Instant access</span></div>
        </div>
    </section>
    <section class="detail-specs">
        <h2>Artwork Details</h2>
        <div><span>♧ &nbsp; File format</span><strong>{{ $artwork->digital_file_path ? strtoupper(pathinfo($artwork->digital_file_path, PATHINFO_EXTENSION)) : 'PNG' }}</strong></div>
        <div><span>▣ &nbsp; Resolution</span><strong>{{ $artwork->dimensions ?: 'High resolution' }}</strong></div>
        <div><span>▦ &nbsp; Category</span><strong>{{ $artwork->category }}</strong></div>
        <div><span>♙ &nbsp; License</span><strong>Personal use</strong></div>
    </section>
    @if ($related->isNotEmpty())
        <section class="section">
            <div class="section-heading"><div><span class="eyebrow">More to love</span><h2>More from {{ $artwork->category }}</h2></div></div>
            <div class="art-grid">@foreach ($related as $item) @include('components.artwork-card', ['artwork' => $item]) @endforeach</div>
        </section>
    @endif
</div>
@endsection
