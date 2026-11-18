<?php

// Ang base class ng lahat ng migration.
use Illuminate\Database\Migrations\Migration;
// Ang Blueprint, ginagamit sa pagdedefine ng mga column ng table.
use Illuminate\Database\Schema\Blueprint;
// Ang Schema facade, ginagamit sa paggawa o pagbura ng table.
use Illuminate\Support\Facades\Schema;

// Migration = version control ng database. Ang class na ito ay walang pangalan (anonymous class) at extends Migration.
return new class extends Migration
{
    // up() = tumatakbo kapag "php artisan migrate". Dito ginagawa ang table.
    public function up(): void
    {
        // Gumawa ng table na "products". Ang $table ay ang blueprint ng mga column nito.
        Schema::create('products', function (Blueprint $table) {
            // Primary key na "id": auto-increment na buong numero, natatangi sa bawat row.
            $table->id();
            // Pangalan ng produkto: text na hanggang 255 na letra (default ng string).
            $table->string('name');
            // SKU (Stock Keeping Unit): code ng produkto. unique() = hindi pwedeng magkapareho sa dalawang produkto.
            $table->string('sku')->unique();
            // Paglalarawan: mahabang text. nullable() = pwedeng blangko.
            $table->text('description')->nullable();
            // Category (hal. Lumber, Tools): text na kailangan.
            $table->string('category');
            // Dami sa stock: positibong buong numero (unsigned), default ay 0 kapag walang ibinigay.
            $table->unsignedInteger('quantity')->default(0);
            // Reorder level: kapag ang quantity ay umabot dito o bumaba, Low Stock na. Default ay 10.
            $table->unsignedInteger('reorder_level')->default(10);
            // Presyo kada piraso: decimal na hanggang 10 digit, 2 ang nasa likod ng decimal point (hal. 185.00).
            $table->decimal('unit_price', 10, 2);
            // Supplier: pangalan ng supplier. nullable() = opsyonal.
            $table->string('supplier')->nullable();
            // Awtomatikong gumagawa ng created_at at updated_at na column.
            $table->timestamps();
        });
    }

    // down() = tumatakbo kapag "php artisan migrate:rollback". Binabawi ang ginawa ng up().
    public function down(): void
    {
        // Burahin ang products table kung nandoon (dropIfExists = hindi mag-e-error kung wala).
        Schema::dropIfExists('products');
    }
};