<?php

// Sinasabi kung nasaan ang class na ito (folder app/Models). Kailangan ito para mahanap ng Laravel ang class.
namespace App\Models;

// Kinukuha ang base class na Model ng Eloquent. Dito nanggagaling ang create(), update(), delete(), find(), atbp.
use Illuminate\Database\Eloquent\Model;
// Kinukuha ang uri ng relationship na "hasMany" para sa return type ng transactions().
use Illuminate\Database\Eloquent\Relations\HasMany;

// Ang class na Product ay kumakatawan sa "products" table sa database. Awtomatikong "products" ang table name dahil sa pangalan ng class.
class Product extends Model
{
    // $fillable = listahan ng mga column na pwedeng i-save nang sabay-sabay (mass assignment), halimbawa sa Product::create($validated).
    // Mga column na wala dito ay hindi masasave, kaya proteksyon ito laban sa hindi inaasahang data.
    protected $fillable = [
        'name', 'sku', 'description', 'category',       // pangalan, SKU (unique code), paglalarawan, uri ng produkto
        'quantity', 'reorder_level', 'unit_price', 'supplier', // dami sa stock, antas kung kailan mag-re-reorder, presyo kada piraso, supplier
    ];

    // $casts = ginagawang tamang uri ang data kapag kinuha sa database.
    protected $casts = [
        'unit_price' => 'decimal:2', // laging may 2 decimal places ang presyo (hal. 185.00)
    ];

    // RELATIONSHIP: isang product ay maraming inventory transactions (stock in/out).
    public function transactions(): HasMany
    {
        // Ang foreign key na product_id sa inventory_transactions table ang nag-uugnay dito.
        return $this->hasMany(InventoryTransaction::class);
    }

    // BUSINESS LOGIC: True kapag mababa na ang stock pero hindi pa zero.
    public function isLowStock(): bool
    {
        // Mas malaki sa 0 AT hindi lalampas sa reorder level ang quantity.
        return $this->quantity > 0 && $this->quantity <= $this->reorder_level;
    }

    // BUSINESS LOGIC: True kapag wala nang stock.
    public function isOutOfStock(): bool
    {
        // Zero ang quantity.
        return $this->quantity == 0;
    }

    // ACCESSOR: nagbibigay ng $product->status na parang column, pero kinakalkula lang.
    // Ang "get...Attribute" na pangalan ang nagsasabi kay Laravel na accessor ito.
    public function getStatusAttribute(): string
    {
        // Kapag zero ang stock, "Out of Stock".
        if ($this->isOutOfStock()) return 'Out of Stock';
        // Kapag mababa ang stock, "Low Stock".
        if ($this->isLowStock()) return 'Low Stock';
        // Kung hindi, sapat pa ang stock.
        return 'In Stock';
    }

    // ACCESSOR: $product->total_value = halaga ng lahat ng stock ng produktong ito.
    public function getTotalValueAttribute(): float
    {
        // Dami x presyo kada piraso.
        return $this->quantity * $this->unit_price;
    }
}