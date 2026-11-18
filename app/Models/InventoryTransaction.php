<?php

// Nasa folder app/Models ang class na ito. Kailangan ito para mahanap ng Laravel ang class.
namespace App\Models;

// Base class ng lahat ng Eloquent model. Dito nanggagaling ang create(), save(), find(), atbp.
use Illuminate\Database\Eloquent\Model;
// Uri ng relationship na "belongsTo" (ang transaction ay pag-aari ng isang product/user).
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Kumakatawan sa "inventory_transactions" table: bawat row ay isang stock in o stock out.
class InventoryTransaction extends Model
{
    // Mga column na pwedeng i-save nang sabay-sabay (mass assignment), halimbawa sa $product->transactions()->create([...]).
    // product_id = aling produkto, type = 'in' o 'out', quantity = ilan ang pumasok/lumabas,
    // reference = dokumento (hal. PO-1001), user_id = sino ang gumawa ng transaction.
    protected $fillable = ['product_id', 'type', 'quantity', 'reference', 'user_id'];

    // RELATIONSHIP: ang transaction ay pag-aari ng isang Product.
    public function product(): BelongsTo
    {
        // Gamit ang product_id column para hanapin ang Product.
        return $this->belongsTo(Product::class);
    }

    // RELATIONSHIP: ang transaction ay ginawa ng isang User (para sa accountability).
    public function user(): BelongsTo
    {
        // Gamit ang user_id column para hanapin ang User. Ginagamit ito sa show page: $t->user->name.
        return $this->belongsTo(User::class);
    }
}