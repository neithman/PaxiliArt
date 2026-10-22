@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
<div class="page-wrap" style="max-width:1000px">
    <div class="section-heading" style="margin-top:30px">
        <div><span class="eyebrow">One last little thing</span><h2>Check out</h2></div>
        <a class="plain-link" href="{{ route('cart.index') }}">← Back to cart</a>
    </div>
    <div class="steps">
        <span class="step"><span class="step-number">✓</span> Cart</span><span class="muted">——</span>
        <span class="step active"><span class="step-number">2</span> Check out</span><span class="muted">——</span>
        <span class="step"><span class="step-number">3</span> Payment</span>
    </div>
    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf
        <div class="checkout-grid">
            <section class="panel">
                <h2>Billing information</h2>
                <div class="field"><label for="billing_name">Full name *</label><input id="billing_name" name="billing_name" value="{{ old('billing_name', auth()->user()->name) }}" required></div>
                <div class="field"><label for="billing_email">Email address *</label><input id="billing_email" name="billing_email" type="email" value="{{ old('billing_email', auth()->user()->email) }}" required></div>
                <h2 style="margin-top:26px">Payment method</h2>
                <div class="payment-list">
                    @foreach (['GCash' => 'G', 'Maya' => '◢', 'Credit/Debit Card' => '▤', 'PayPal' => 'P'] as $method => $mark)
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="{{ $method }}" @checked(old('payment_method', 'GCash') === $method)>
                            <span class="payment-brand" style="color:{{ $method === 'GCash' ? '#1661ae' : ($method === 'Maya' ? '#14735f' : '#273f77') }}">{{ $mark }} &nbsp;{{ $method }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="payment-note"><strong>Test checkout only.</strong> This demo records an order for testing and does not charge your GCash, Maya, card, or PayPal account.</div>
                <button class="button" type="submit" style="width:100%;margin-top:17px">Place demo order · ₱{{ number_format($items->sum(fn ($item) => (float) $item->artwork->price), 2) }}</button>
            </section>
            <aside class="panel">
                <h2>Your order</h2>
                <div class="line-items">
                    @foreach ($items as $item)
                        <div class="line-item">
                            <img src="{{ $item->artwork->cover_url }}" alt="{{ $item->artwork->title }}">
                            <div class="line-info"><strong>{{ $item->artwork->title }}</strong><span>by {{ $item->artwork->artist->name }}</span></div>
                            <strong>₱{{ number_format((float) $item->artwork->price, 2) }}</strong>
                        </div>
                    @endforeach
                </div>
                <div class="total-row"><span>Subtotal</span><span>₱{{ number_format($items->sum(fn ($item) => (float) $item->artwork->price), 2) }}</span></div>
                <div class="total-row"><span>Total</span><span style="color:var(--orange)">₱{{ number_format($items->sum(fn ($item) => (float) $item->artwork->price), 2) }}</span></div>
                <a class="summary-link" href="{{ route('cart.index') }}">Edit your cart</a>
            </aside>
        </div>
    </form>
</div>
@endsection
