<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->currentCart($request);
        $items = $cart->items()->with(['product', 'variant'])->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $items->sum(fn (CartItem $item) => $item->unitPrice() * $item->quantity);

        return view('checkout.index', [
            'items' => $items,
            'subtotal' => $subtotal,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_first_name' => ['required', 'string', 'max:255'],
            'customer_last_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'shipping_address_line1' => ['required', 'string', 'max:255'],
            'shipping_address_line2' => ['nullable', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:120'],
            'shipping_state' => ['required', 'string', 'max:120'],
            'shipping_postal_code' => ['required', 'string', 'max:20'],
            'order_note' => ['nullable', 'string', 'max:1000'],
        ], [], [
            'customer_first_name' => 'first name',
            'customer_last_name' => 'last name',
            'customer_email' => 'email',
            'customer_phone' => 'phone',
            'shipping_address_line1' => 'address',
            'shipping_address_line2' => 'address line 2',
            'shipping_city' => 'city',
            'shipping_state' => 'state',
            'shipping_postal_code' => 'PIN code',
        ]);

        $validated['customer_name'] = trim($validated['customer_first_name'].' '.$validated['customer_last_name']);
        unset($validated['customer_first_name'], $validated['customer_last_name']);

        $cart = $this->currentCart($request);
        $items = $cart->items()->with(['product', 'variant'])->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        foreach ($items as $item) {
            if ($item->quantity > $item->availableStock()) {
                return redirect()->route('cart.index')
                    ->with('error', "\"{$item->product->title}\" no longer has enough stock. Please update your cart.");
            }
        }

        $subtotal = $items->sum(fn (CartItem $item) => $item->unitPrice() * $item->quantity);
        $shippingFee = 0;

        $order = DB::transaction(function () use ($validated, $items, $subtotal, $shippingFee, $cart) {
            $order = Order::create([
                ...$validated,
                'order_number' => $this->generateOrderNumber(),
                'shipping_country' => 'India',
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $subtotal + $shippingFee,
                'payment_method' => 'cod',
                'status' => 'placed',
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_title' => $item->product->title,
                    'sku' => $item->variant?->sku ?? $item->product->sku,
                    'price' => $item->unitPrice(),
                    'quantity' => $item->quantity,
                    'subtotal' => $item->unitPrice() * $item->quantity,
                ]);

                if ($item->variant) {
                    $item->variant->decrement('stock_quantity', $item->quantity);
                } else {
                    $item->product->decrement('stock_quantity', $item->quantity);
                }
            }

            $cart->items()->delete();

            return $order;
        });

        return redirect()->route('checkout.confirmation', $order)->with('success', 'Order placed successfully.');
    }

    public function confirmation(Order $order)
    {
        $order->load('items');

        return view('checkout.confirmation', compact('order'));
    }

    private function currentCart(Request $request): Cart
    {
        return Cart::firstOrCreate(['session_id' => $request->session()->getId()]);
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-'.now()->format('Ymd').'-'.strtoupper(Str::random(5));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
