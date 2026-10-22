<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PaxiliArt') · PaxiliArt</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<header class="site-header">
    <a class="brand brand-image" href="{{ route('home') }}" aria-label="PaxiliArt home"><img src="{{ asset('images/brand-logo.png') }}" alt="PaxiliArt"></a>
    <nav class="header-nav" aria-label="Main navigation">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('marketplace.index') }}">Marketplace</a>
        <a href="{{ route('artists.index') }}">Artist</a>
        <a href="{{ route('seller.dashboard') }}">Sell Art</a>
    </nav>
    <form class="search-form" action="{{ route('marketplace.index') }}" method="GET">
        <button type="submit" aria-label="Search">⌕</button>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search artwork, artist..." aria-label="Search artwork and artists">
    </form>
    <div class="header-spacer"></div>
    <div class="header-actions">
        <button class="mobile-menu" type="button" data-search-toggle aria-label="Toggle search">⌕</button>
        <a class="header-link sell-link" href="{{ route('seller.dashboard') }}">Sell</a>
        @auth
            <a class="icon-link" href="{{ route('cart.index') }}" aria-label="Shopping cart">🛒<span class="cart-count">{{ auth()->user()->cartItems()->count() }}</span></a>
            <a class="avatar-chip" href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('seller.dashboard') }}">
                <span class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <span class="header-link">{{ auth()->user()->name }}</span>
            </a>
            <a class="header-link order-link" href="{{ route('orders.index') }}">My orders</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="plain-link" style="border:0;background:none;cursor:pointer;font-size:12px" type="submit">Log out</button>
            </form>
        @else
            <a class="header-link auth-link" href="{{ route('login') }}">Log in</a>
            <a class="button button-small" href="{{ route('register') }}">Create account</a>
        @endauth
    </div>
</header>

@if (session('success'))
    <div class="page-wrap"><div class="alert">{{ session('success') }}</div></div>
@endif
@if (session('error'))
    <div class="page-wrap"><div class="alert alert-error">{{ session('error') }}</div></div>
@endif
@if ($errors->any())
    <div class="page-wrap">
        <div class="validation-errors">
            <strong>Please check the details below.</strong>
            <ul style="margin:7px 0 0;padding-left:18px">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    </div>
@endif

<main class="page-main">@yield('content')</main>

<footer class="site-footer">
    <div class="page-wrap footer-inner">
        <a class="brand brand-image" href="{{ route('home') }}" aria-label="PaxiliArt home"><img src="{{ asset('images/brand-logo.png') }}" alt="PaxiliArt"></a>
        <span class="footer-copy">A little home for art made with heart. &copy; {{ now()->year }} PaxiliArt.</span>
        <a class="plain-link" href="{{ route('seller.dashboard') }}">Share your art <span aria-hidden="true">↗</span></a>
    </div>
</footer>
</body>
</html>
