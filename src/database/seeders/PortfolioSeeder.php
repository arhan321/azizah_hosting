<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Portfolio;
use App\Models\Category;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            $this->command->warn('Tidak ada kategori. Jalankan seeder kategori terlebih dahulu.');
            return;
        }

        $portfolios = [
            [
                'title' => 'Kaligrafi Masjid Al-Ikhlas',
                'description' => 'Proyek kaligrafi mural untuk Masjid Al-Ikhlas dengan desain kaligrafi Ayat Kursi berukuran 5x3 meter. Menggunakan cat akrilik premium dengan finishing glossy yang tahan lama.',
                'image_url' => 'https://via.placeholder.com/800x600/1a1a2e/B8860B?text=Masjid+Al-Ikhlas',
                'category_id' => $categories->random()->id,
                'client_name' => 'Masjid Al-Ikhlas',
                'location' => 'Jakarta Selatan',
                'completion_date' => now()->subMonths(2),
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'title' => 'Kaligrafi Kantor PT Berkah Jaya',
                'description' => 'Desain kaligrafi modern untuk ruang meeting kantor dengan tema motivasi dan doa. Kombinasi warna gold dan hitam yang elegan.',
                'image_url' => 'https://via.placeholder.com/800x600/1a1a2e/B8860B?text=PT+Berkah+Jaya',
                'category_id' => $categories->random()->id,
                'client_name' => 'PT Berkah Jaya',
                'location' => 'Jakarta Pusat',
                'completion_date' => now()->subMonths(1),
                'is_featured' => true,
                'order' => 2,
            ],
            [
                'title' => 'Kaligrafi Rumah Bapak Ahmad',
                'description' => 'Kaligrafi untuk ruang tamu dengan desain klasik. Menggunakan teknik hand painting dengan detail yang sangat halus dan presisi tinggi.',
                'image_url' => 'https://via.placeholder.com/800x600/1a1a2e/B8860B?text=Rumah+Pak+Ahmad',
                'category_id' => $categories->random()->id,
                'client_name' => 'Bapak Ahmad Hidayat',
                'location' => 'Tangerang',
                'completion_date' => now()->subWeeks(3),
                'is_featured' => false,
                'order' => 3,
            ],
            [
                'title' => 'Kaligrafi Islamic Center',
                'description' => 'Proyek besar untuk Islamic Center dengan multiple panel kaligrafi di berbagai ruangan. Total area pengerjaan mencapai 50 meter persegi.',
                'image_url' => 'https://via.placeholder.com/800x600/1a1a2e/B8860B?text=Islamic+Center',
                'category_id' => $categories->random()->id,
                'client_name' => 'Islamic Center Jakarta',
                'location' => 'Jakarta Timur',
                'completion_date' => now()->subMonths(4),
                'is_featured' => true,
                'order' => 4,
            ],
            [
                'title' => 'Kaligrafi Cafe Syariah',
                'description' => 'Desain kaligrafi kontemporer untuk cafe dengan konsep syariah. Perpaduan seni kaligrafi dengan interior modern yang menarik.',
                'image_url' => 'https://via.placeholder.com/800x600/1a1a2e/B8860B?text=Cafe+Syariah',
                'category_id' => $categories->random()->id,
                'client_name' => 'Cafe Barokah',
                'location' => 'Bekasi',
                'completion_date' => now()->subWeeks(2),
                'is_featured' => false,
                'order' => 5,
            ],
        ];

        foreach ($portfolios as $portfolio) {
            Portfolio::create($portfolio);
        }

        $this->command->info('Portfolio seeder berhasil dijalankan!');
    }
}
