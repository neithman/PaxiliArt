@extends('layouts.app')
@section('title', 'Admin dashboard')
@section('content')
<div class="dashboard-layout">
    <aside class="sidebar">
        <a class="sidebar-brand" href="{{ route('home') }}">PaxiliArt</a>
        <nav class="sidebar-nav">
            <a class="active" href="{{ route('admin.dashboard') }}">▦ &nbsp; Dashboard</a>
            <a href="{{ route('admin.dashboard') }}#review">▧ &nbsp; Artwork review <span class="status status-pending">{{ $stats['pending'] }}</span></a>
            <a href="{{ route('admin.orders') }}">▤ &nbsp; Orders</a>
            <a href="{{ route('admin.users') }}">♙ &nbsp; Users</a>
            <a href="{{ route('home') }}">⌂ &nbsp; View marketplace</a>
        </nav>
        <div class="sidebar-bottom">{{ auth()->user()->name }}<br><span class="muted">Administrator</span></div>
    </aside>
    <section class="dashboard-main">
        <div class="dashboard-top"><div><span class="eyebrow">Your marketplace at a glance</span><h1>Welcome, {{ auth()->user()->name }}!</h1></div><a class="button button-small" href="{{ route('admin.orders') }}">View all orders</a></div>
        <p class="muted small">Here's what's happening in your PaxiliArt marketplace today.</p>
        <div class="dashboard-stats">
            <div class="stat-card"><span class="stat-icon">💸</span><div class="stat-label">Demo sales</div><div class="stat-value">₱{{ number_format((float) $stats['sales'], 0) }}</div></div>
            <div class="stat-card"><span class="stat-icon">▣</span><div class="stat-label">Total orders</div><div class="stat-value">{{ number_format($stats['orders']) }}</div></div>
            <div class="stat-card"><span class="stat-icon">♙</span><div class="stat-label">Total users</div><div class="stat-value">{{ number_format($stats['users']) }}</div></div>
            <div class="stat-card"><span class="stat-icon">▧</span><div class="stat-label">Total artworks</div><div class="stat-value">{{ number_format($stats['artworks']) }}</div></div>
        </div>
        <div class="dashboard-columns">
            <section class="dashboard-card">
                <div class="section-heading"><div><h2>Sales overview</h2><span class="muted small">Recorded demo orders over the last seven days</span></div><span class="status">Test checkout</span></div>
                @php
                    $days = collect(range(0, 6))->map(fn ($offset) => now()->subDays(6 - $offset));
                    $values = $days->map(fn ($day) => (float) ($chart->get($day->format('D')) ?? 0));
                    $maxValue = max(1, $values->max() ?: 0);
                    $points = $values->map(fn ($value, $index) => ($index * 100).','.round(160 - ($value / $maxValue * 125), 1))->implode(' ');
                @endphp
                <div class="chart-box">
                    <svg viewBox="0 0 600 200" role="img" aria-label="Sales chart for the past seven days">
                        <line class="chart-grid" x1="0" y1="35" x2="600" y2="35"></line>
                        <line class="chart-grid" x1="0" y1="75" x2="600" y2="75"></line>
                        <line class="chart-grid" x1="0" y1="115" x2="600" y2="115"></line>
                        <line class="chart-grid" x1="0" y1="155" x2="600" y2="155"></line>
                        <polyline class="chart-line" points="{{ $points }}"></polyline>
                        @foreach ($values as $index => $value)
                            <circle cx="{{ $index * 100 }}" cy="{{ round(160 - ($value / $maxValue * 125), 1) }}" r="4" fill="#ed5a2d"></circle>
                            <text x="{{ $index * 100 }}" y="190" text-anchor="middle" fill="#89847a" font-size="10">{{ $days[$index]->format('M j') }}</text>
                        @endforeach
                    </svg>
                </div>
            </section>
            <section class="dashboard-card">
                <div class="section-heading"><h2>Recent orders</h2><a class="plain-link" href="{{ route('admin.orders') }}">View all</a></div>
                @if ($recentOrders->isNotEmpty())
                    <div class="top-art-list">
                        @foreach ($recentOrders as $order)
                            <div class="top-art-row">
                                @if ($order->items->first())
                                    <img src="{{ str_starts_with($order->items->first()->cover_image, 'artworks/covers/') ? Storage::disk('public')->url($order->items->first()->cover_image) : asset($order->items->first()->cover_image) }}" alt="">
                                @endif
                                <div class="top-art-copy"><strong>{{ $order->buyer->name }}</strong><span>{{ $order->reference }} · ₱{{ number_format((float) $order->subtotal, 2) }}</span></div>
                                <span class="status {{ $order->payment_status === 'pending' ? 'status-pending' : '' }}">{{ $order->payment_status === 'pending' ? 'Pending' : 'Paid · demo' }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="muted small">New demo orders will show up here.</p>
                @endif
            </section>
        </div>
        <div class="dashboard-columns">
            <section class="dashboard-card">
                <div class="section-heading"><h2>Top selling artwork</h2><a class="plain-link" href="{{ route('home') }}">View market</a></div>
                @if ($topArtworks->isNotEmpty())
                    <div class="top-art-list">
                        @foreach ($topArtworks as $artwork)
                            <div class="top-art-row"><img src="{{ $artwork->cover_url }}" alt=""><div class="top-art-copy"><strong>{{ $artwork->title }}</strong><span>by {{ $artwork->artist->name }} · {{ $artwork->sales_count }} sales</span></div><b>₱{{ number_format((float) $artwork->price, 0) }}</b></div>
                        @endforeach
                    </div>
                @else
                    <p class="muted small">Published artworks will appear here.</p>
                @endif
            </section>
            <section class="dashboard-card" id="review">
                <div class="section-heading"><div><h2>Artwork to review</h2><span class="muted small">{{ $stats['pending'] }} waiting for approval</span></div></div>
                @forelse ($pendingArtworks as $artwork)
                    <div class="top-art-row" style="margin:12px 0">
                        <img src="{{ $artwork->cover_url }}" alt="">
                        <div class="top-art-copy"><strong>{{ $artwork->title }}</strong><span>by {{ $artwork->artist->name }}</span></div>
                    </div>
                    <div style="display:flex;gap:7px;margin-bottom:13px">
                        <form method="POST" action="{{ route('admin.artworks.review', $artwork) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="published"><button class="button button-small" type="submit">Approve</button></form>
                        <form method="POST" action="{{ route('admin.artworks.review', $artwork) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="rejected"><button class="button button-small button-danger" type="submit">Reject</button></form>
                    </div>
                @empty
                    <p class="muted small">All caught up. No artwork is waiting for review.</p>
                @endforelse
            </section>
        </div>
    </section>
</div>
@endsection
