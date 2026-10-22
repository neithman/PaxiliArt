@extends('layouts.app')
@section('title', $artwork->exists ? 'Edit artwork' : 'Add artwork')
@section('content')
<div class="page-wrap">
    <section class="panel product-form">
        <a class="plain-link small" href="{{ route('seller.dashboard') }}">← Back to your studio</a>
        <span class="eyebrow" style="display:block;margin-top:17px">Share something original</span>
        <h1 style="font-size:32px;margin:6px 0 8px">{{ $artwork->exists ? 'Edit your artwork' : 'Add new artwork' }}</h1>
        <p class="muted small">Artwork edits are reviewed before appearing in the market.</p>
        <form method="POST" enctype="multipart/form-data" action="{{ $artwork->exists ? route('seller.artworks.update', $artwork) : route('seller.artworks.store') }}">
            @csrf
            @if ($artwork->exists) @method('PUT') @endif
            <div class="product-form-grid">
                <div class="field span-two"><label for="title">Artwork title *</label><input id="title" name="title" maxlength="120" value="{{ old('title', $artwork->title) }}" required placeholder="Give your artwork a name"></div>
                <div class="field"><label for="category">Category *</label><select id="category" name="category" required>
                    @foreach (['Digital illustration', 'Photography', '3D art', 'Abstract', 'Character design'] as $category)
                        <option value="{{ $category }}" @selected(old('category', $artwork->category) === $category)>{{ $category }}</option>
                    @endforeach
                </select></div>
                <div class="field"><label for="price">Price in Philippine pesos (₱) *</label><input id="price" name="price" type="number" min="1" max="100000" step="0.01" value="{{ old('price', $artwork->price) }}" required placeholder="200.00"></div>
                <div class="field span-two"><label for="description">About this artwork *</label><textarea id="description" name="description" maxlength="4000" required placeholder="Tell collectors about the piece, your process, and what makes it special.">{{ old('description', $artwork->description) }}</textarea></div>
                <div class="field"><label for="dimensions">Size or dimensions</label><input id="dimensions" name="dimensions" value="{{ old('dimensions', $artwork->dimensions) }}" placeholder="e.g. 3000 × 3000 px"></div>
                <div class="field"><label for="cover_image">Artwork preview {{ $artwork->exists ? '' : '*' }}</label><input id="cover_image" name="cover_image" type="file" accept=".jpg,.jpeg,.png,.webp" {{ $artwork->exists ? '' : 'required' }}><span class="file-help">JPG, PNG or WebP · up to 10 MB. This image appears in the marketplace.</span></div>
                <div class="field span-two"><label for="digital_file">Digital download file</label><input id="digital_file" name="digital_file" type="file" accept=".zip,.pdf,.psd,.ai,.glb,.stl"><span class="file-help">Optional · ZIP, PDF, PSD, AI, GLB or STL · up to 50 MB. Buyers will receive it privately after placing an order.</span></div>
            </div>
            <button class="button" type="submit">{{ $artwork->exists ? 'Save changes for review' : 'Submit artwork for review' }}</button>
        </form>
    </section>
</div>
@endsection
