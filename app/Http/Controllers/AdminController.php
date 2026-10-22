<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $stats = [
            'sales' => Order::where('payment_status', 'demo')->sum('subtotal'),
            'orders' => Order::count(),
            'users' => User::count(),
            'artworks' => Artwork::count(),
            'published' => Artwork::where('status', 'published')->count(),
            'pending' => Artwork::where('status', 'pending')->count(),
        ];

        $recentOrders = Order::with('buyer', 'items')->latest()->take(6)->get();
        $topArtworks = Artwork::with('artist')->where('status', 'published')->orderByDesc('sales_count')->take(5)->get();
        $pendingArtworks = Artwork::with('artist')->where('status', 'pending')->latest()->take(6)->get();
        $chart = Order::where('payment_status', 'demo')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->get()
            ->groupBy(fn ($order) => $order->created_at->format('D'))
            ->map(fn ($orders) => (float) $orders->sum('subtotal'));

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topArtworks', 'pendingArtworks', 'chart'));
    }

    public function reviewArtwork(Request $request, Artwork $artwork): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['published', 'rejected'])],
        ]);

        $artwork->update($data);

        return back()->with('success', 'Artwork review updated.');
    }

    public function orders(): View
    {
        $orders = Order::with('buyer', 'items')->latest()->paginate(20);

        return view('admin.orders', compact('orders'));
    }

    public function users(): View
    {
        $users = User::withCount('artworks', 'orders')->latest()->paginate(20);

        return view('admin.users', compact('users'));
    }
}
