<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->currentCart($request);
        $items = $cart->items()->with(['product.media', 'variant'])->get();

        $subtotal = $items->sum(fn (CartItem $item) => $item->unitPrice() * $item->quantity);

        return view('cart.index', [
            'items' => $items,
            'subtotal' => $subtotal,
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
            'product_variant_id' => ['nullable', 'exists:product_variants,id'],
        ]);

        $quantity = $validated['quantity'] ?? 1;
        $variantId = $validated['product_variant_id'] ?? null;

        $variant = $variantId ? $product->variants()->find($variantId) : null;
        if ($variantId && ! $variant) {
            return back()->with('error', 'Selected option is not available.');
        }

        $availableStock = $variant?->stock_quantity ?? $product->stock_quantity;
        if ($availableStock <= 0) {
            return back()->with('error', 'This product is out of stock.');
        }

        $cart = $this->currentCart($request);

        $item = $cart->items()->firstOrNew([
            'product_id' => $product->id,
            'product_variant_id' => $variant?->id,
        ]);
        $item->quantity = min($availableStock, ($item->exists ? $item->quantity : 0) + $quantity);
        $item->save();

        return back()->with('success', 'Added to cart.');
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->authorizeItem($request, $cartItem);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $availableStock = $cartItem->availableStock();
        $cartItem->update([
            'quantity' => min($validated['quantity'], max($availableStock, 1)),
        ]);

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->authorizeItem($request, $cartItem);

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    private function currentCart(Request $request): Cart
    {
        return Cart::firstOrCreate(['session_id' => $request->session()->getId()]);
    }

    private function authorizeItem(Request $request, CartItem $cartItem): void
    {
        abort_unless($cartItem->cart->session_id === $request->session()->getId(), 403);
    }
}
