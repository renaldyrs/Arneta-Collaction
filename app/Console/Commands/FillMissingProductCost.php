<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class FillMissingProductCost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:fill-missing-cost {--percent=60 : Persentase dari price yang digunakan sebagai cost (default 60)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Isi nilai cost untuk produk yang kosong/0 menggunakan persentase dari price.';

    public function handle()
    {
        $percent = (float) $this->option('percent');
        if ($percent <= 0 || $percent > 100) {
            $this->error('Persentase harus antara 1 dan 100');
            return 1;
        }

        $multiplier = $percent / 100.0;

        $products = Product::with('sizes')->get();
        $updated = 0;
        $skipped = 0;

        foreach ($products as $p) {
            // hitung stok efektif
            $effectiveStock = 0;
            if ($p->relationLoaded('sizes') && $p->sizes->count() > 0) {
                $effectiveStock = $p->sizes->sum('pivot.stock');
            } else {
                $effectiveStock = (int) ($p->stock ?? 0);
            }

            if ((!isset($p->cost) || $p->cost == 0) && $effectiveStock > 0) {
                $p->cost = $p->price * $multiplier;
                $p->save();
                $updated++;
                $this->info("Updated: {$p->id} - {$p->name} => cost={$p->cost}");
            } else {
                $skipped++;
            }
        }

        $this->newLine();
        $this->info("Selesai. Produk diperbarui: {$updated}. Produk dilewati: {$skipped}.");
        return 0;
    }
}
