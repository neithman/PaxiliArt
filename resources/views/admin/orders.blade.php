@extends('layouts.app')
@section('title', 'Orders')
@section('content')
<div class="dashboard-layout">
    <aside class="sidebar"><a class="sidebar-brand" href="{{ route('home') }}">PaxiliArt</a><nav class="sidebar-nav"><a href="{{ route('admin.dashboard') }}">▦ &nbsp; Dashboard</a><a class="active" href="{{ route('admin.orders') }}">▤ &nbsp; Orders</a><a href="{{ route('admin.users') }}">♙ &nbsp; Users</a><a href="{{ route('home') }}">⌂ &nbsp; View marketplace</a></nav><div class="sidebar-bottom">Administrator</div></aside>
    <section class="dashboard-main">
        <div class="dashboard-top"><div><span class="eyebrow">Marketplace activity</span><h1>Orders</h1></div><a class="button button-outline button-small" href="{{ route('admin.dashboard') }}">← Dashboard</a></div>
        <p class="muted small">Orders created with the local test checkout. No real payments are taken.</p>
        <div class="table-wrap"><table class="data-table"><thead><tr><th>Order ID</th><th>Customer</th><th>Items</th><th>Payment method</th><th>Total</th><th>Status</th><th>Date</th></tr></thead><tbody>
            @forelse ($orders as $order)
                <tr><td><strong>{{ $order->reference }}</strong></td><td>{{ $order->billing_name }}<br><span class="muted">{{ $order->billing_email }}</span></td><td>{{ $order->items->count() }}</td><td>{{ $order->payment_method }} <span class="muted">(demo)</span></td><td>₱{{ number_format((float) $order->subtotal, 2) }}</td><td><span class="status {{ $order->payment_status === 'pending' ? 'status-pending' : '' }}">{{ $order->payment_status === 'pending' ? 'Pending' : 'Completed · demo' }}</span></td><td>{{ $order->created_at->format('M j, Y') }}</td></tr>
            @empty
                <tr><td colspan="7" class="muted">No orders yet.</td></tr>
            @endforelse
        </tbody></table></div>
        {{ $orders->links() }}
    </section>
</div>
@endsection
