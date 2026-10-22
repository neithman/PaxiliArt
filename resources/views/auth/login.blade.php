@extends('layouts.app')
@section('title', 'Log in')
@section('content')
<div class="page-wrap">
    <div class="auth-shell">
        <div class="auth-art"><div class="auth-art-copy"><span class="eyebrow" style="color:#fff">A little color for your day</span><h2>Make room for a little wonder.</h2><p class="small">Art made by independent artists, collected by you.</p></div></div>
        <div class="auth-panel">
            <form class="auth-form" method="POST" action="{{ route('login.store') }}">
                @csrf
                <a class="brand brand-image auth-brand" href="{{ route('home') }}"><img src="{{ asset('images/brand-logo.png') }}" alt="PaxiliArt"></a>
                <h1>Log in to your account</h1>
                <p class="muted small">Welcome back. Your next favorite piece is waiting.</p>
                <div class="auth-tabs"><a class="active" href="{{ route('login') }}">Log in</a><a href="{{ route('register') }}">Create account</a></div>
                <div class="field"><label for="email">Email address</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus placeholder="you@example.com"></div>
                <div class="field"><label for="password">Password</label><input id="password" name="password" type="password" autocomplete="current-password" required placeholder="Your password"></div>
                <label class="small muted"><input type="checkbox" name="remember" value="1"> Remember me</label>
                <div style="text-align:right;margin-top:7px"><a class="plain-link small" href="{{ route('password.request') }}">Forgot password?</a></div>
                <button class="button" type="submit">Log in</button>
                <p class="form-note" style="margin-top:18px">New to PaxiliArt? <a href="{{ route('register') }}">Create an account</a></p>
            </form>
        </div>
    </div>
</div>
@endsection
