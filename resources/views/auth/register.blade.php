@extends('layouts.app')
@section('title', 'Create account')
@section('content')
<div class="page-wrap">
    <div class="auth-shell">
        <div class="auth-art"><div class="auth-art-copy"><span class="eyebrow" style="color:#fff">Your art story starts here</span><h2>Collect something that feels like you.</h2><p class="small">A home for original work and the people who love it.</p></div></div>
        <div class="auth-panel">
            <form class="auth-form" method="POST" action="{{ route('register.store') }}">
                @csrf
                <a class="brand brand-image auth-brand" href="{{ route('home') }}"><img src="{{ asset('images/brand-logo.png') }}" alt="PaxiliArt"></a>
                <h1>Create your account</h1>
                <p class="muted small">Join a little community of art lovers.</p>
                <div class="auth-tabs"><a href="{{ route('login') }}">Log in</a><span class="active">Register</span></div>
                <div class="field"><label for="name">Full name</label><input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required placeholder="Your name"></div>
                <div class="field"><label for="email">Email address</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required placeholder="you@example.com"></div>
                <div class="field"><label for="password">Password</label><input id="password" name="password" type="password" autocomplete="new-password" required placeholder="At least 8 characters"></div>
                <div class="field"><label for="password_confirmation">Confirm password</label><input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required placeholder="Type it one more time"></div>
                <button class="button" type="submit">Create account</button>
                <p class="form-note" style="margin-top:18px">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
            </form>
        </div>
    </div>
</div>
@endsection
