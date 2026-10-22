<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $category = $request->query('category');
        $sort = $request->query('sort', 'newest');
        $isMarketplace = $request->routeIs('marketplace.index');

        $artworks = Artwork::with('artist')
            ->where('status', 'published')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhereHas('artist', fn ($artist) => $artist->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($category, fn ($query) => $query->where('category', $category))
            ->when($sort === 'price_low', fn ($query) => $query->orderBy('price'))
            ->when($sort === 'price_high', fn ($query) => $query->orderByDesc('price'))
            ->when($sort === 'popular', fn ($query) => $query->orderByDesc('sales_count'))
            ->when($sort === 'featured', fn ($query) => $query->orderByDesc('is_featured')->latest())
            ->when($sort === 'newest', fn ($query) => $query->latest())
            ->paginate(12)
            ->withQueryString();

        $featured = Artwork::with('artist')
            ->where('status', 'published')
            ->where('is_featured', true)
            ->orderByDesc('sales_count')
            ->take(4)
            ->get();

        $categories = Artwork::where('status', 'published')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('marketplace.index', compact('artworks', 'featured', 'categories', 'search', 'category', 'sort', 'isMarketplace'));
    }

    public function show(Artwork $artwork): View
    {
        abort_unless($artwork->status === 'published', 404);

        $artwork->load('artist');
        $related = Artwork::with('artist')
            ->where('status', 'published')
            ->where('category', $artwork->category)
            ->whereKeyNot($artwork->id)
            ->take(4)
            ->get();

        return view('artworks.show', compact('artwork', 'related'));
    }

    public function artist(User $artist): View
    {
        $artworks = $artist->artworks()
            ->with('artist')
            ->where('status', 'published')
            ->latest()
            ->paginate(12);

        return view('artists.show', compact('artist', 'artworks'));
    }

    public function artists(): View
    {
        $artists = User::where('role', 'seller')
            ->withCount(['artworks as published_artworks_count' => fn (Builder $query) => $query->where('status', 'published')])
            ->orderByDesc('published_artworks_count')
            ->paginate(12);

        return view('artists.index', compact('artists'));
    }
}
