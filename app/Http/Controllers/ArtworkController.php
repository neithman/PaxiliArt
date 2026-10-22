<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ArtworkController extends Controller
{
    public function create(Request $request): View
    {
        $this->ensureSeller($request);

        return view('seller.artworks.form', ['artwork' => new Artwork]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureSeller($request);
        $data = $this->validatedData($request);
        $data['cover_image'] = $request->file('cover_image')->store('artworks/covers', 'public');
        $data['digital_file_path'] = $request->file('digital_file')?->store('artworks/files', 'local');
        $data['artist_id'] = $request->user()->id;
        $data['slug'] = Str::slug($data['title']).'-'.Str::lower(Str::random(6));
        $data['status'] = 'pending';
        $data['is_featured'] = false;
        $data['sales_count'] = 0;

        Artwork::create($data);

        return redirect()->route('seller.dashboard')->with('success', 'Artwork submitted for review.');
    }

    public function edit(Request $request, Artwork $artwork): View
    {
        $this->ensureSeller($request);
        abort_unless($artwork->artist_id === $request->user()->id, 404);

        return view('seller.artworks.form', compact('artwork'));
    }

    public function update(Request $request, Artwork $artwork): RedirectResponse
    {
        $this->ensureSeller($request);
        abort_unless($artwork->artist_id === $request->user()->id, 404);
        $data = $this->validatedData($request, $artwork);

        if ($request->hasFile('cover_image')) {
            $coverIsInUse = $artwork->orderItems()->where('cover_image', $artwork->cover_image)->exists();
            if (! $coverIsInUse && str_starts_with($artwork->cover_image, 'artworks/covers/')) {
                Storage::disk('public')->delete($artwork->cover_image);
            }

            $data['cover_image'] = $request->file('cover_image')->store('artworks/covers', 'public');
        }

        if ($request->hasFile('digital_file')) {
            $fileIsInUse = $artwork->orderItems()->where('digital_file_path', $artwork->digital_file_path)->exists();
            if ($artwork->digital_file_path && ! $fileIsInUse) {
                Storage::disk('local')->delete($artwork->digital_file_path);
            }
            $data['digital_file_path'] = $request->file('digital_file')->store('artworks/files', 'local');
        }

        $data['status'] = 'pending';
        $artwork->update($data);

        return redirect()->route('seller.dashboard')->with('success', 'Changes saved and sent for review.');
    }

    public function destroy(Request $request, Artwork $artwork): RedirectResponse
    {
        $this->ensureSeller($request);
        abort_unless($artwork->artist_id === $request->user()->id, 404);

        if (! $artwork->orderItems()->exists()) {
            if (str_starts_with($artwork->cover_image, 'artworks/covers/')) {
                Storage::disk('public')->delete($artwork->cover_image);
            }

            if ($artwork->digital_file_path) {
                Storage::disk('local')->delete($artwork->digital_file_path);
            }
        }

        $artwork->delete();

        return back()->with('success', 'Artwork removed from your studio.');
    }

    private function ensureSeller(Request $request): void
    {
        abort_unless(in_array($request->user()->role, ['seller', 'admin'], true), 403);
    }

    private function validatedData(Request $request, ?Artwork $artwork = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:4000'],
            'category' => ['required', Rule::in(['Sci-Fi', 'Anime', 'Fantasy', 'Monsters', 'Others'])],
            'price' => ['required', 'numeric', 'min:1', 'max:100000'],
            'dimensions' => ['nullable', 'string', 'max:40'],
            'cover_image' => [$artwork ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'digital_file' => ['nullable', 'file', 'mimes:zip,pdf,psd,ai,glb,stl', 'max:51200'],
        ]);
    }
}
