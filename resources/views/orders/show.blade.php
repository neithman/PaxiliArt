@extends('layouts.app')
@section('title', 'Order '.$order->reference)
@section('content')
<div class="page-wrap">
    <section class="order-confirm">
        <div class="order-confirm-header">
            <div class="checkmark">✧</div>
            <span class="eyebrow">Thank you for supporting independent art</span>
            <h1>Your demo order is ready!</h1>
            <p class="muted small" style="margin:0">No payment was charged. Your digital files are ready below.</p>
        </div>
        <div class="order-meta">
            <div class="panel"><span>Order number</span><strong>{{ $order->reference }}</strong></div>
            <div class="panel"><span>Payment method · demo</span><strong>{{ $order->payment_method }}</strong></div>
            <div class="panel"><span>Total</span><strong>₱{{ number_format((float) $order->subtotal, 2) }}</strong></div>
        </div>
        <div class="panel">
            <h2>Your digital art</h2>
            <div class="line-items">
                @foreach ($order->items as $item)
                    <div class="line-item">
                        <img src="{{ str_starts_with($item->cover_image, 'artworks/covers/') ? Storage::disk('public')->url($item->cover_image) : asset($item->cover_image) }}" alt="{{ $item->title }}">
                        <div class="line-info"><strong>{{ $item->title }}</strong><span>by {{ $item->artist_name }} · instant digital download</span></div>
                        <a class="button button-small" href="{{ route('orders.download', [$order, $item]) }}">Download</a>
                    </div>
                @endforeach
            </div>
        </div>
        <p class="muted small" style="text-align:center;margin-top:22px"><a class="plain-link" href="{{ route('home') }}">← Back to the art market</a></p>
    </section>
</div>
@endsection
