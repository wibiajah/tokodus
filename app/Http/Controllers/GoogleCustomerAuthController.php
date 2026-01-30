<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\WelcomeGoogleUser;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleCustomerAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            Log::info('Google Login Attempt', [
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId()
            ]);

            $customer = Customer::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($customer) {
                // Customer sudah ada
                Log::info('Customer exists', [
                    'customer_id' => $customer->id,
                    'email_verified' => $customer->email_verified_at ? 'YES' : 'NO'
                ]);
                
                // ✅ CEK APAKAH BELUM VERIFIED
                if (!$customer->hasVerifiedEmail()) {
                    
                    // Link Google ID jika belum
                    if (!$customer->google_id) {
                        $customer->update(['google_id' => $googleUser->getId()]);
                        Log::info('Google ID linked to existing customer');
                    }
                    
                    // ✅ KIRIM ULANG EMAIL VERIFICATION
                    try {
                        Log::info('Sending verification email to: ' . $customer->email);
                        
                        // Kirim email verification Laravel
                        $customer->sendEmailVerificationNotification();
                        Log::info('Laravel verification email sent');
                        
                        // Kirim welcome email
                       
                    } catch (Exception $mailError) {
                        Log::error('Email sending failed', [
                            'error' => $mailError->getMessage(),
                            'trace' => $mailError->getTraceAsString()
                        ]);
                    }
                    
                    return redirect()->route('home')
                        ->with('error', 'Email Anda belum diverifikasi! Kami telah mengirim ulang email verifikasi. Silakan cek inbox & spam folder Anda.');
                }
                
                // Customer sudah verified
                if (!$customer->google_id) {
                    $customer->update(['google_id' => $googleUser->getId()]);
                }
                
            } else {
                // Customer baru - register via Google
                Log::info('Creating new customer from Google');
                
                $nameParts = explode(' ', $googleUser->getName(), 2);
                $username = strtolower(str_replace('.', '', explode('@', $googleUser->getEmail())[0])) . '_' . rand(10000, 99999);

                $customer = Customer::create([
                    'firstname' => $nameParts[0],
                    'lastname' => $nameParts[1] ?? '',
                    'username' => $username,
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => null,
                    'email_verified_at' => null,
                ]);

                Log::info('New customer created', [
                    'customer_id' => $customer->id,
                    'email' => $customer->email
                ]);

                // ✅ KIRIM EMAIL VERIFICATION
                try {
                    Log::info('Sending verification email to NEW customer: ' . $customer->email);
                    
                    // Kirim email verification Laravel
                    $customer->sendEmailVerificationNotification();
                    Log::info('Laravel verification email sent to new customer');
                    
                   
                } catch (Exception $mailError) {
                    Log::error('Email sending failed for new customer', [
                        'error' => $mailError->getMessage(),
                        'trace' => $mailError->getTraceAsString()
                    ]);
                }

                return redirect()->route('home')
                    ->with('info', 'Akun berhasil dibuat! Silakan cek email Anda (termasuk folder spam) untuk verifikasi sebelum login.');
            }

            // Login customer (hanya jika sudah verified)
            Auth::guard('customer')->login($customer);
            Log::info('Customer logged in successfully', ['customer_id' => $customer->id]);

            return redirect()->route('home')
                ->with('success', 'Selamat datang kembali, ' . $customer->firstname . '!');

        } catch (Exception $e) {
            Log::error('Google login failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('home')
                ->with('error', 'Login Google gagal: ' . $e->getMessage());
        }
    }
}