<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user()->orders()->with('items')->latest()->paginate(12);

        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($order->buyer_id === $request->user()->id, 404);

        $order->load('items');

        return view('orders.show', compact('order'));
    }

    public function download(Request $request, Order $order, OrderItem $orderItem): StreamedResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        abort_unless($order->buyer_id === $request->user()->id, 404);
        abort_unless($orderItem->order_id === $order->id && $order->payment_status === 'demo', 404);

        if ($orderItem->digital_file_path && Storage::disk('local')->exists($orderItem->digital_file_path)) {
            return Storage::disk('local')->download($orderItem->digital_file_path);
        }

        if (str_starts_with($orderItem->cover_image, 'artworks/covers/')) {
            abort_unless(Storage::disk('public')->exists($orderItem->cover_image), 404);

            return Storage::disk('public')->download($orderItem->cover_image);
        }

        $preview = public_path($orderItem->cover_image);
        abort_unless(is_file($preview), 404);

        return response()->download($preview);
    }
}
