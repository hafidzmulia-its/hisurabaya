<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth page
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            \Log::info('Google OAuth user data:', [
                'id' => $googleUser->id,
                'email' => $googleUser->email,
                'name' => $googleUser->name,
            ]);
            
            // Find or create user
            $user = User::where('google_id', $googleUser->id)
                       ->orWhere('email', $googleUser->email)
                       ->first();
            
            if ($user) {
                // Update existing user with Google ID if not set
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'email_verified_at' => now(),
                    ]);
                }
            } else {
                // Create new user
                // Generate username from email
                $username = explode('@', $googleUser->email)[0];
                $baseUsername = $username;
                $counter = 1;
                
                // Ensure unique username
                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }
                
                \Log::info('Creating new user with username:', ['username' => $username]);
                
                $user = User::create([
                    'name' => $googleUser->name,
                    'username' => $username,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'email_verified_at' => now(),
                    'password' => null, // No password for Google users
                    'role' => 'user', // Default role
                ]);
            }
            
            // Login the user
            Auth::login($user, true);
            
            \Log::info('User logged in successfully:', ['user_id' => $user->id]);
            
            return redirect()->intended('/dashboard');
            
        } catch (Exception $e) {
            \Log::error('Google OAuth error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/login')->with('error', 'Unable to login with Google. Please try again. Error: ' . $e->getMessage());
        }
    }
}
