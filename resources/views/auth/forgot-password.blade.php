@extends('layouts.app')
@section('title', 'Reset password')
@section('content')
<div class="page-wrap">
    <div class="auth-shell">
        <div class="auth-art"><div class="auth-art-copy"><span class="eyebrow" style="color:#fff">Find your way back</span><h2>A little color is waiting.</h2></div></div>
        <div class="auth-panel">
            <form class="auth-form" method="POST" action="{{ route('password.email') }}">
                @csrf
                <a class="brand brand-image auth-brand" href="{{ route('home') }}"><img src="{{ asset('images/brand-logo.png') }}" alt="PaxiliArt"></a>
                <h1>Forgot your password?</h1>
                <p class="muted small">Enter your email and we'll send you a link to make a new one.</p>
                <div class="field"><label for="email">Email address</label><input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email"></div>
                <button class="button" type="submit">Send password reset link</button>
                <p class="form-note" style="margin-top:18px"><a href="{{ route('login') }}">Back to log in</a></p>
            </form>
        </div>
    </div>
</div>
@endsection
