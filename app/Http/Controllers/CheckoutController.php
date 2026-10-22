<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $items = $request->user()->cartItems()->with('artwork.artist')
            ->whereHas('artwork', fn ($query) => $query->where('status', 'published'))
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('checkout.index', compact('items'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'billing_name' => ['required', 'string', 'max:120'],
            'billing_email' => ['required', 'email', 'max:255'],
            'payment_method' => ['required', Rule::in(['GCash', 'Maya', 'Credit/Debit Card', 'PayPal'])],
        ]);

        $order = DB::transaction(function () use ($request, $data) {
            $items = $request->user()->cartItems()
                ->with('artwork.artist')
                ->lockForUpdate()
                ->get()
                ->filter(fn ($item) => $item->artwork?->status === 'published');

            if ($items->isEmpty()) {
                return null;
            }

            $totalCents = $items->sum(fn ($item) => (int) round((float) $item->artwork->price * 100));
            $order = Order::create([
                'buyer_id' => $request->user()->id,
                'reference' => 'PA-'.Str::upper(Str::random(10)),
                'billing_name' => $data['billing_name'],
                'billing_email' => $data['billing_email'],
                'payment_method' => $data['payment_method'],
                'payment_status' => 'demo',
                'status' => 'completed_demo',
                'subtotal' => number_format($totalCents / 100, 2, '.', ''),
                'completed_at' => now(),
            ]);

            foreach ($items as $item) {
                $artwork = $item->artwork;
                $order->items()->create([
                    'artwork_id' => $artwork->id,
                    'title' => $artwork->title,
                    'artist_name' => $artwork->artist->name,
                    'cover_image' => $artwork->cover_image,
                    'digital_file_path' => $artwork->digital_file_path,
                    'price' => $artwork->price,
                ]);
                $artwork->increment('sales_count');
            }

            $request->user()->cartItems()->delete();

            return $order;
        });

        if (! $order) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty or no longer contains available artwork.');
        }

        return redirect()->route('orders.show', $order)->with('success', 'Demo order complete. No payment was charged.');
    }
}
