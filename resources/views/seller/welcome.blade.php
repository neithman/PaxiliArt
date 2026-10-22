@extends('layouts.app')
@section('title', 'Become an artist')
@section('content')
<div class="page-wrap">
    <section class="panel seller-welcome">
        <div class="welcome-illo">🎨</div>
        <span class="eyebrow">Your studio is waiting</span>
        <h1>Make space for your art.</h1>
        <p>Open your PaxiliArt studio to share original work with people who are excited to discover it. Set your own prices, upload your files, and keep track of your sales.</p>
        <form method="POST" action="{{ route('seller.activate') }}">@csrf<button class="button" type="submit">Open my artist studio <span aria-hidden="true">→</span></button></form>
    </section>
</div>
@endsection
