<?php

// Nasa folder database/seeders ang class na ito.
namespace Database\Seeders;

// Ang base class ng lahat ng seeder.
use Illuminate\Database\Seeder;

// DatabaseSeeder = ang "pangunahing" seeder. Ito ang tinatawag kapag "php artisan db:seed" (walang --class).
class DatabaseSeeder extends Seeder
{
    // run() = tumatakbo kapag "php artisan db:seed".
    public function run(): void
    {
        // Tinatawag ang ProductSeeder para ilagay ang 18 sample na produkto sa database.
        // Kung may iba pang seeder sa hinaharap (hal. UserSeeder), idadagdag lang dito ang $this->call(...).
        $this->call(ProductSeeder::class);
    }
}