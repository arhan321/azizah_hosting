<?php

namespace Tests\Unit;

use App\Models\CustomOrderFile;
use App\Models\Design;
use App\Models\OrderResult;
use App\Models\Payment;
use App\Models\Portfolio;
use App\Services\FileUploadService;
use App\Support\StoragePath;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoragePathTest extends TestCase
{
    public function test_it_normalizes_legacy_local_storage_values(): void
    {
        $expected = 'uploads/designs/example.png';
        $values = [
            'https://azizah.djncloud.my.id/storage/uploads/designs/example.png',
            '/storage/uploads/designs/example.png',
            'storage/uploads/designs/example.png',
            'public/storage/uploads/designs/example.png',
            'public/uploads/designs/example.png',
            'uploads\\designs\\example.png',
        ];

        foreach ($values as $value) {
            $this->assertSame($expected, StoragePath::normalize($value));
        }
    }

    public function test_it_keeps_external_urls_unchanged(): void
    {
        $url = 'https://cdn.example.com/images/example.png';

        $this->assertSame($url, StoragePath::normalize($url));
        $this->assertSame($url, StoragePath::publicUrl($url));
    }

    public function test_models_save_paths_and_expose_browser_urls(): void
    {
        Storage::fake('public');

        $legacyUrl = 'https://azizah.djncloud.my.id/storage/uploads/designs/example.png';
        $path = 'uploads/designs/example.png';

        $design = new Design(['image_url' => $legacyUrl]);
        $portfolio = new Portfolio(['image_url' => $legacyUrl]);
        $customFile = new CustomOrderFile(['file_url' => $legacyUrl]);
        $payment = new Payment(['payment_proof' => $legacyUrl]);
        $result = new OrderResult(['file_url' => $legacyUrl]);

        $this->assertSame($path, $design->getAttributes()['image_url']);
        $this->assertSame($path, $portfolio->getAttributes()['image_url']);
        $this->assertSame($path, $customFile->getAttributes()['file_url']);
        $this->assertSame($path, $payment->getAttributes()['payment_proof']);
        $this->assertSame($path, $result->getAttributes()['file_url']);

        $this->assertSame('/storage/uploads/designs/example.png', $design->image_url);
        $this->assertSame('/storage/uploads/designs/example.png', $portfolio->image_url);
        $this->assertSame('/storage/uploads/designs/example.png', $customFile->file_url);
        $this->assertSame('/storage/uploads/designs/example.png', $payment->payment_proof_url);
        $this->assertSame('/storage/uploads/designs/example.png', $result->download_url);
    }

    public function test_file_service_deletes_legacy_url_values_from_public_disk(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('uploads/designs/example.png', 'image');

        $service = $this->app->make(FileUploadService::class);

        $this->assertTrue($service->delete(
            'https://azizah.djncloud.my.id/storage/uploads/designs/example.png'
        ));
        Storage::disk('public')->assertMissing('uploads/designs/example.png');
    }
}