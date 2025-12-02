<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function logout(Request $request)
    {
        // Logout langsung
        Auth::logout();
        
        // Invalidate session
        $request->session()->invalidate();
        
        // Regenerate token
        $request->session()->regenerateToken();

        // Return JSON response (bukan redirect)
        return response()->json(['success' => true], 200);
    }
}