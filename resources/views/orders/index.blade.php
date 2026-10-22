@extends('layouts.app')
@section('title', 'My orders')
@section('content')
<div class="page-wrap" style="max-width:1000px">
    <section class="section" style="margin-top:36px">
        <div class="section-heading"><div><span class="eyebrow">Your art collection</span><h2>My orders</h2></div><a class="plain-link" href="{{ route('marketplace.index') }}">Explore the marketplace →</a></div>
        @if ($orders->isNotEmpty())
            <div class="table-wrap"><table class="data-table">
                <thead><tr><th>Order</th><th>Artwork</th><th>Payment method</th><th>Total</th><th>Date</th><th></th></tr></thead>
                <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td><strong>{{ $order->reference }}</strong><br><span class="status">Demo order</span></td>
                        <td>{{ $order->items->count() }} {{ $order->items->count() === 1 ? 'artwork' : 'artworks' }}</td>
                        <td>{{ $order->payment_method }} <span class="muted">(demo)</span></td>
                        <td>₱{{ number_format((float) $order->subtotal, 2) }}</td>
                        <td>{{ $order->created_at->format('M j, Y') }}</td>
                        <td><a class="plain-link" href="{{ route('orders.show', $order) }}">View &amp; download</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table></div>
            {{ $orders->links() }}
        @else
            <div class="empty-state"><div class="empty-icon">♡</div><h3>Your collection starts with one click</h3><p class="muted small">Orders and purchased artwork downloads will appear here.</p><a class="button" href="{{ route('marketplace.index') }}">Discover artwork</a></div>
        @endif
    </section>
</div>
@endsection
