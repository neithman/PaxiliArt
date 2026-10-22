@extends('layouts.app')
@section('title', 'Artist studio')
@section('content')
<div class="dashboard-layout">
    <aside class="sidebar">
        <a class="sidebar-brand" href="{{ route('home') }}">PaxiliArt</a>
        <nav class="sidebar-nav">
            <a class="active" href="{{ route('seller.dashboard') }}">▦ &nbsp; My studio</a>
            <a href="{{ route('seller.artworks.create') }}">＋ &nbsp; Add artwork</a>
            <a href="{{ route('home') }}">⌂ &nbsp; Visit market</a>
        </nav>
        <div class="sidebar-bottom">{{ auth()->user()->name }}<br><span class="muted">Independent artist</span></div>
    </aside>
    <section class="dashboard-main">
        <div class="dashboard-top"><div><span class="eyebrow">Your creative corner</span><h1>Welcome, {{ auth()->user()->name }}!</h1></div><a class="button button-small" href="{{ route('seller.artworks.create') }}">＋ Add new artwork</a></div>
        <p class="muted small">A little look at what's happening in your studio.</p>
        <div class="dashboard-stats">
            <div class="stat-card"><span class="stat-icon">✧</span><div class="stat-label">Total artworks</div><div class="stat-value">{{ $artworks->count() }}</div></div>
            <div class="stat-card"><span class="stat-icon">◉</span><div class="stat-label">Published</div><div class="stat-value">{{ $artworks->where('status', 'published')->count() }}</div></div>
            <div class="stat-card"><span class="stat-icon">◷</span><div class="stat-label">Pending review</div><div class="stat-value">{{ $artworks->where('status', 'pending')->count() }}</div></div>
            <div class="stat-card"><span class="stat-icon">♡</span><div class="stat-label">Demo sales</div><div class="stat-value">{{ $sales->count() }}</div></div>
        </div>
        <div class="dashboard-columns">
            <section class="dashboard-card">
                <div class="section-heading"><div><h2>Your artworks</h2><span class="muted small">Make edits or check the review status.</span></div></div>
                @if ($artworks->isNotEmpty())
                    <div class="table-wrap"><table class="data-table"><thead><tr><th>Artwork</th><th>Price</th><th>Status</th><th>Action</th></tr></thead><tbody>
                    @foreach ($artworks as $artwork)
                        <tr>
                            <td><span class="table-art"><img src="{{ $artwork->cover_url }}" alt=""><strong>{{ $artwork->title }}</strong></span></td>
                            <td>₱{{ number_format((float) $artwork->price, 2) }}</td>
                            <td><span class="status {{ $artwork->status === 'pending' ? 'status-pending' : ($artwork->status === 'rejected' ? 'status-rejected' : '') }}">{{ ucfirst($artwork->status) }}</span></td>
                            <td><a class="plain-link" href="{{ route('seller.artworks.edit', $artwork) }}">Edit</a> · <form method="POST" action="{{ route('seller.artworks.destroy', $artwork) }}" style="display:inline">@csrf @method('DELETE')<button data-confirm="Remove this artwork from your studio?" class="plain-link" style="border:0;background:none;cursor:pointer" type="submit">Remove</button></form></td>
                        </tr>
                    @endforeach
                    </tbody></table></div>
                @else
                    <div class="empty-state"><h3>Your gallery is still a blank canvas</h3><a class="button button-small" href="{{ route('seller.artworks.create') }}">Upload your first artwork</a></div>
                @endif
            </section>
            <section class="dashboard-card">
                <h2>Recent demo sales</h2>
                @if ($sales->isNotEmpty())
                    <div class="top-art-list">
                        @foreach ($sales as $sale)
                            <div class="top-art-row"><img src="{{ str_starts_with($sale->cover_image, 'artworks/covers/') ? Storage::disk('public')->url($sale->cover_image) : asset($sale->cover_image) }}" alt=""><div class="top-art-copy"><strong>{{ $sale->title }}</strong><span>₱{{ number_format((float) $sale->price, 2) }} · test order</span></div></div>
                        @endforeach
                    </div>
                @else
                    <p class="muted small">Your completed test orders will show here.</p>
                @endif
            </section>
        </div>
        <div class="dashboard-card"><span class="eyebrow">Want to see what buyers see?</span><h2 style="margin-top:8px">Discover the marketplace</h2><a class="plain-link" href="{{ route('home') }}">Browse the art collection →</a></div>
    </section>
</div>
@endsection
