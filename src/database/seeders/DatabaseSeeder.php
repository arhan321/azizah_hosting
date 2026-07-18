<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Design;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ─── Admin ───────────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@aqlamMural.com'],
            [
                'name'     => 'Admin Aqlam Mural',
                'phone'    => '081234567890',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        // ─── Demo pelanggan ───────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'pelanggan@example.com'],
            [
                'name'     => 'Budi Santoso',
                'phone'    => '082345678901',
                'password' => Hash::make('password'),
                'role'     => 'customer',
            ]
        );

        // ─── Kategori ─────────────────────────────────────────────────────────
        $categories = [
            ['name' => 'Kaligrafi Islami',    'slug' => 'kaligrafi-islami'],
            ['name' => 'Kaligrafi Asmaul Husna', 'slug' => 'asmaul-husna'],
            ['name' => 'Mural Dekoratif',     'slug' => 'mural-dekoratif'],
            ['name' => 'Kaligrafi Masjid',    'slug' => 'kaligrafi-masjid'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // ─── Desain contoh ────────────────────────────────────────────────────
        $cat1 = Category::where('slug', 'kaligrafi-islami')->first();
        $cat2 = Category::where('slug', 'asmaul-husna')->first();
        $cat3 = Category::where('slug', 'mural-dekoratif')->first();
        $cat4 = Category::where('slug', 'kaligrafi-masjid')->first();

        $designs = [
            ['category_id' => $cat1->id, 'name' => 'Ayat Kursi Klasik',         'price' => 3500000,  'description' => 'Kaligrafi Ayat Kursi gaya klasik dengan ornament emas.'],
            ['category_id' => $cat1->id, 'name' => 'Bismillah Modern',           'price' => 1500000,  'description' => 'Desain Bismillah kontemporer cocok untuk ruang tamu.'],
            ['category_id' => $cat2->id, 'name' => 'Asmaul Husna Lengkap',       'price' => 8000000,  'description' => '99 nama Allah dalam satu panel besar.'],
            ['category_id' => $cat2->id, 'name' => 'Ar-Rahman Ornamental',       'price' => 2500000,  'description' => 'Nama Allah Ar-Rahman dengan ornament bunga.'],
            ['category_id' => $cat3->id, 'name' => 'Mural Floral Islami',        'price' => 5000000,  'description' => 'Kombinasi kaligrafi dan ornament floral warna-warni.'],
            ['category_id' => $cat4->id, 'name' => 'Kaligrafi Kubah Masjid',     'price' => 15000000, 'description' => 'Khusus interior kubah masjid, tingkat kompleksitas tinggi.'],
            ['category_id' => $cat4->id, 'name' => 'Mihrab Calligraphy Wall',    'price' => 10000000, 'description' => 'Dinding mihrab dengan kaligrafi full-wall.'],
            ['category_id' => $cat1->id, 'name' => 'Surah Al-Fatihah',           'price' => 4000000,  'description' => 'Surah pembuka Al-Quran dalam satu panel.'],
        ];

        foreach ($designs as $d) {
            $slug = Str::slug($d['name']) . '-' . Str::random(5);
            Design::firstOrCreate(
                ['name' => $d['name'], 'category_id' => $d['category_id']],
                array_merge($d, ['slug' => $slug])
            );
        }
    }
}

