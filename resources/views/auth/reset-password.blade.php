@extends('layouts.app')
@section('title', 'Choose a new password')
@section('content')
<div class="page-wrap">
    <div class="auth-shell">
        <div class="auth-art"><div class="auth-art-copy"><span class="eyebrow" style="color:#fff">A fresh start</span><h2>Make room for a little wonder.</h2></div></div>
        <div class="auth-panel">
            <form class="auth-form" method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <a class="brand brand-image auth-brand" href="{{ route('home') }}"><img src="{{ asset('images/brand-logo.png') }}" alt="PaxiliArt"></a>
                <h1>Choose a new password</h1>
                <div class="field"><label for="email">Email address</label><input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autocomplete="email"></div>
                <div class="field"><label for="password">New password</label><input id="password" name="password" type="password" required autocomplete="new-password"></div>
                <div class="field"><label for="password_confirmation">Confirm new password</label><input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"></div>
                <button class="button" type="submit">Reset password</button>
            </form>
        </div>
    </div>
</div>
@endsection
