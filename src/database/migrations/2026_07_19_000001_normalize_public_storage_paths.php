<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove the application host and /storage prefix from legacy file data.
     */
    public function up(): void
    {
        $fileColumns = [
            'designs' => 'image_url',
            'portfolios' => 'image_url',
            'custom_order_files' => 'file_url',
            'order_results' => 'file_url',
            'payments' => 'payment_proof',
        ];

        foreach ($fileColumns as $table => $column) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
                continue;
            }

            DB::table($table)
                ->select('id', $column)
                ->whereNotNull($column)
                ->where($column, '!=', '')
                ->chunkById(200, function ($rows) use ($table, $column): void {
                    foreach ($rows as $row) {
                        $currentValue = $row->{$column};
                        $normalizedValue = $this->normalizeStoragePath($currentValue);

                        if ($normalizedValue !== $currentValue) {
                            DB::table($table)
                                ->where('id', $row->id)
                                ->update([$column => $normalizedValue]);
                        }
                    }
                });
        }
    }

    /**
     * Restoring a deployment-specific host would make the data less portable.
     */
    public function down(): void
    {
        // Intentionally left blank.
    }

    private function normalizeStoragePath(string $value): string
    {
        $value = trim(str_replace('\\', '/', $value));

        if (preg_match('#^https?://#i', $value) === 1) {
            $urlPath = parse_url($value, PHP_URL_PATH);
            $storageMarker = '/storage/';

            if (!is_string($urlPath) || !str_contains($urlPath, $storageMarker)) {
                return $value;
            }

            $value = substr(
                $urlPath,
                strpos($urlPath, $storageMarker) + strlen($storageMarker)
            );
        }

        $value = preg_replace('#^/?(?:public/)?storage/#i', '', $value) ?? $value;
        $value = preg_replace('#^public/#i', '', $value) ?? $value;

        return ltrim($value, '/');
    }
};
