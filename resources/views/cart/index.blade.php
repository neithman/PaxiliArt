@extends('layouts.app')
@section('title', 'Your cart')
@section('content')
<div class="page-wrap">
    <section class="section" style="max-width:980px;margin:38px auto">
        <div class="section-heading"><div><span class="eyebrow">Almost yours</span><h2>Your cart</h2></div><a class="plain-link" href="{{ route('home') }}">← Keep exploring</a></div>
        @if ($items->isEmpty())
            <div class="empty-state"><div class="empty-icon">♡</div><h3>Your cart is waiting for something wonderful</h3><p class="muted small">Find an original piece and bring a little magic home.</p><a class="button" href="{{ route('home') }}">Discover artwork</a></div>
        @else
            <div class="checkout-grid" style="grid-template-columns:1.4fr .8fr">
                <section class="panel">
                    <div class="line-items">
                        @foreach ($items as $item)
                            <div class="line-item">
                                <a href="{{ route('artworks.show', $item->artwork) }}"><img src="{{ $item->artwork->cover_url }}" alt="{{ $item->artwork->title }}"></a>
                                <div class="line-info"><strong>{{ $item->artwork->title }}</strong><span>by {{ $item->artwork->artist->name }}</span></div>
                                <strong>₱{{ number_format((float) $item->artwork->price, 2) }}</strong>
                                <form method="POST" action="{{ route('cart.destroy', $item->artwork) }}">@csrf @method('DELETE')<button class="plain-link" style="border:0;background:none;cursor:pointer" type="submit" aria-label="Remove {{ $item->artwork->title }}">Remove</button></form>
                            </div>
                        @endforeach
                    </div>
                </section>
                <aside class="panel">
                    <h2>Order summary</h2>
                    <div class="total-row" style="border-top:0;margin-top:0;padding-top:3px"><span>{{ $items->count() }} {{ $items->count() === 1 ? 'artwork' : 'artworks' }}</span><span>₱{{ number_format($items->sum(fn ($item) => (float) $item->artwork->price), 2) }}</span></div>
                    <div class="total-row"><span>Total</span><span class="price" style="color:var(--orange)">₱{{ number_format($items->sum(fn ($item) => (float) $item->artwork->price), 2) }}</span></div>
                    <a class="button" style="width:100%;margin-top:18px" href="{{ route('checkout.index') }}">Continue to checkout</a>
                    <p class="muted small" style="margin:13px 0 0;line-height:1.5">Your digital art will be ready to download after checkout.</p>
                </aside>
            </div>
        @endif
    </section>
</div>
@endsection
