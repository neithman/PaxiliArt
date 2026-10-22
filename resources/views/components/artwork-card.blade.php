<article class="art-card">
    <a class="art-card-image" href="{{ route('artworks.show', $artwork) }}">
        <img src="{{ $artwork->cover_url }}" alt="{{ $artwork->title }}" loading="lazy">
        @if ($artwork->is_featured)<span class="badge">FEATURED</span>@endif
    </a>
    <div class="art-card-info">
        <a class="art-card-title" href="{{ route('artworks.show', $artwork) }}">{{ $artwork->title }}</a>
        <div class="art-card-artist">by <a href="{{ route('artists.show', $artwork->artist) }}">{{ $artwork->artist->name }}</a></div>
        <div class="art-card-bottom">
            <span class="price"><small>₱</small>{{ number_format((float) $artwork->price, 0) }}</span>
            @auth
                @if ($artwork->artist_id !== auth()->id())
                    <form action="{{ route('cart.store', $artwork) }}" method="POST">
                        @csrf
                        <button class="add-cart" type="submit">＋ Add to cart</button>
                    </form>
                @endif
            @else
                <a class="add-cart" href="{{ route('login') }}">＋ Add to cart</a>
            @endauth
        </div>
    </div>
</article>
