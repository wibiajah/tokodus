<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if user can view any orders
     */
    public function viewAny(User $user)
    {
        // Super Admin bisa lihat semua orders
        if ($user->role === 'super_admin') {
            return true;
        }

        // Kepala Toko bisa lihat orders tokonya
        if ($user->role === 'kepala_toko' && $user->toko_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if user can view specific order
     */
    public function view(User $user, Order $order)
    {
        // Super Admin bisa lihat semua orders
        if ($user->role === 'super_admin') {
            return true;
        }

        // Kepala Toko hanya bisa lihat order tokonya sendiri
        if ($user->role === 'kepala_toko') {
            return $order->toko_id === $user->toko_id;
        }

        return false;
    }

    /**
     * Determine if user can update order
     */
    public function update(User $user, Order $order)
    {
        // Super Admin bisa update semua orders
        if ($user->role === 'super_admin') {
            return true;
        }

        // Kepala Toko hanya bisa update order tokonya sendiri
        if ($user->role === 'kepala_toko') {
            return $order->toko_id === $user->toko_id;
        }

        return false;
    }

    /**
     * Determine if user can confirm payment
     */
    public function confirmPayment(User $user, Order $order)
    {
        // Super Admin bisa konfirmasi pembayaran semua orders
        if ($user->role === 'super_admin') {
            return true;
        }

        // Kepala Toko hanya bisa konfirmasi pembayaran order tokonya sendiri
        if ($user->role === 'kepala_toko') {
            return $order->toko_id === $user->toko_id;
        }

        return false;
    }

    /**
     * Determine if user can delete order
     */
    public function delete(User $user, Order $order)
    {
        // Hanya Super Admin yang bisa delete
        return $user->role === 'super_admin';
    }
}