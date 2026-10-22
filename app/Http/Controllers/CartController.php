<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $items = $request->user()->cartItems()->with('artwork.artist')
            ->whereHas('artwork', fn ($query) => $query->where('status', 'published'))
            ->get();

        return view('cart.index', compact('items'));
    }

    public function store(Request $request, Artwork $artwork): RedirectResponse
    {
        abort_unless($artwork->status === 'published', 404);

        if ($artwork->artist_id === $request->user()->id) {
            return back()->with('error', 'You cannot buy your own artwork.');
        }

        $request->user()->cartItems()->firstOrCreate(['artwork_id' => $artwork->id]);

        if ($request->boolean('buy_now')) {
            return redirect()->route('checkout.index')->with('success', 'Artwork added to your cart.');
        }

        return back()->with('success', 'Artwork added to your cart.');
    }

    public function destroy(Request $request, Artwork $artwork): RedirectResponse
    {
        $request->user()->cartItems()->where('artwork_id', $artwork->id)->delete();

        return back()->with('success', 'Artwork removed from your cart.');
    }
}
