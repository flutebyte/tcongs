<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = [
        'session_id',
        'coupon_id',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Re-keys a guest cart onto a new session ID after login. Carts are
     * looked up purely by session_id (see CartController::currentCart), and
     * every login path in this app calls session()->regenerate() for the
     * standard fixation-attack reason — which immediately changes what
     * session()->getId() returns. Without this, a guest who adds an item via
     * Buy It Now, gets sent through OTP login, and lands back on checkout
     * would find an empty cart: the item is still there, just filed under a
     * session_id nothing points to anymore.
     *
     * If a cart already exists under the new session_id (rare — e.g. a device
     * with a leftover authenticated session from a previous user), merges
     * quantities into it instead of overwriting, same firstOrNew+add shape as
     * CartController::store().
     */
    public static function transferSession(string $oldSessionId, string $newSessionId): void
    {
        if ($oldSessionId === $newSessionId) {
            return;
        }

        $old = static::where('session_id', $oldSessionId)->first();
        if (! $old) {
            return;
        }

        $existing = static::where('session_id', $newSessionId)->first();

        if (! $existing) {
            $old->update(['session_id' => $newSessionId]);

            return;
        }

        foreach ($old->items as $item) {
            $target = $existing->items()->firstOrNew([
                'product_id' => $item->product_id,
                'product_variant_id' => $item->product_variant_id,
            ]);
            $target->quantity = ($target->exists ? $target->quantity : 0) + $item->quantity;
            $target->save();
        }

        $old->delete();
    }
}
