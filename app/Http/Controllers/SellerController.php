<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerController extends Controller
{
    public function index(Request $request): View
    {
        if ($request->user()->role === 'customer') {
            return view('seller.welcome');
        }

        if ($request->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $artworks = $request->user()->artworks()->latest()->get();
        $sales = OrderItem::whereHas('artwork', fn ($query) => $query->where('artist_id', $request->user()->id))
            ->whereHas('order', fn ($query) => $query->where('payment_status', 'demo'))
            ->with('order')
            ->latest()
            ->take(8)
            ->get();

        return view('seller.dashboard', compact('artworks', 'sales'));
    }

    public function activate(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'customer', 403);
        $request->user()->update(['role' => 'seller']);

        return redirect()->route('seller.dashboard')->with('success', 'Your artist studio is ready.');
    }
}
