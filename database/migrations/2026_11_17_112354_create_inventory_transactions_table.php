<?php

// Ang base class ng lahat ng migration.
use Illuminate\Database\Migrations\Migration;
// Ang Blueprint, ginagamit sa pagdedefine ng mga column ng table.
use Illuminate\Database\Schema\Blueprint;
// Ang Schema facade, ginagamit sa paggawa o pagbura ng table.
use Illuminate\Support\Facades\Schema;

// Anonymous class na extends Migration. Ito ang version control ng table na "inventory_transactions".
return new class extends Migration
{
    // up() = tumatakbo kapag "php artisan migrate". Dito ginagawa ang table.
    public function up(): void
    {
        // Gumawa ng table na "inventory_transactions": bawat row ay isang stock in o stock out.
        Schema::create('inventory_transactions', function (Blueprint $table) {
            // Primary key na "id": auto-increment, natatangi sa bawat row.
            $table->id();
            // FOREIGN KEY: product_id na nag-uugnay sa products table.
            // constrained() = sinisiguro ng database na ang product_id ay nage-exist sa products table.
            // cascadeOnDelete() = kapag binura ang product, awtomatikong buburahin din ang mga transaction nito.
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            // Uri ng transaction: 'in' (pumasok na stock) o 'out' (lumabas na stock) lang ang pwede.
            $table->enum('type', ['in', 'out']);
            // Ilang piraso ang pumasok o lumabas (positibong buong numero).
            $table->unsignedInteger('quantity');
            // Reference document (hal. purchase order PO-1001). nullable() = pwedeng blangko.
            $table->string('reference')->nullable();
            // FOREIGN KEY: user_id, ang user na gumawa ng transaction (para sa accountability).
            // nullable() = pwedeng blangko. nullOnDelete() = kapag binura ang user, magiging null ito imbes na mabura ang transaction.
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            // Awtomatikong gumagawa ng created_at (kailan ginawa) at updated_at na column.
            $table->timestamps();
        });
    }

    // down() = tumatakbo kapag "php artisan migrate:rollback". Binabawi ang ginawa ng up().
    public function down(): void
    {
        // Burahin ang inventory_transactions table kung nandoon.
        Schema::dropIfExists('inventory_transactions');
    }
};