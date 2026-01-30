<?php

namespace App\Observers;

use App\Models\ProductVariant;

class ProductVariantObserver
{
    /**
     * Handle the ProductVariant "created" event.
     * Auto-update parent stock ketika size baru dibuat
     */
    public function created(ProductVariant $variant): void
    {
        // Jika ini adalah child (size), update parent (color) stock
        if ($variant->parent_id) {
            $this->updateParentStock($variant);
        }
    }

    /**
     * Handle the ProductVariant "updated" event.
     * Auto-update parent stock ketika size diupdate
     */
    public function updated(ProductVariant $variant): void
    {
        // Jika ini adalah child (size), update parent (color) stock
        if ($variant->parent_id) {
            $this->updateParentStock($variant);
        }
    }

    /**
     * Handle the ProductVariant "deleted" event.
     * Auto-update parent stock ketika size dihapus
     */
    public function deleted(ProductVariant $variant): void
    {
        // Jika ini adalah child (size), update parent (color) stock
        if ($variant->parent_id) {
            $this->updateParentStock($variant);
        }
    }

    /**
     * Handle the ProductVariant "restored" event.
     * Auto-update parent stock ketika size direstore (soft delete)
     */
    public function restored(ProductVariant $variant): void
    {
        // Jika ini adalah child (size), update parent (color) stock
        if ($variant->parent_id) {
            $this->updateParentStock($variant);
        }
    }

    /**
     * Handle the ProductVariant "force deleted" event.
     * Auto-update parent stock ketika size dihapus permanent
     */
    public function forceDeleted(ProductVariant $variant): void
    {
        // Jika ini adalah child (size), update parent (color) stock
        if ($variant->parent_id) {
            $this->updateParentStock($variant);
        }
    }

    /**
     * Update parent (color) stock based on children (sizes) stock
     */
    private function updateParentStock(ProductVariant $variant): void
    {
        // Get parent
        $parent = ProductVariant::find($variant->parent_id);

        if (!$parent) {
            return;
        }

        // Calculate total stock dari semua children
        $totalStock = ProductVariant::where('parent_id', $parent->id)
            ->sum('stock_pusat');

        // Update parent stock_pusat (tanpa trigger observer lagi)
        $parent->withoutEvents(function () use ($parent, $totalStock) {
            $parent->update(['stock_pusat' => $totalStock]);
        });
    }
}