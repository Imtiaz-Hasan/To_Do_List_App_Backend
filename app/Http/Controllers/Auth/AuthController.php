<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);
        
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        
            $token = $user->createToken('auth_token')->plainTextToken;
        
            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 201);
        } catch (ValidationException $e) {
            // Extract the first validation error message
            $errors = $e->errors();
            $firstErrorKey = array_key_first($errors); // Get the first key
            $firstErrorMessage = $errors[$firstErrorKey][0]; // Get the first error message
        
            return response()->json([
                'message' => $firstErrorMessage, // Return only the first error message
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The provided credentials are incorrect.'
                ], 401);
            }
        
            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
        
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful! Welcome back ' . $user->name,
                'user' => $user,
                'token' => $token,
            ]);            
        } catch (ValidationException $e) {
            // Extract the first validation error message
            $errors = $e->errors();
            $firstErrorKey = array_key_first($errors); // Get the first key
            $firstErrorMessage = $errors[$firstErrorKey][0]; // Get the first error message
        
            return response()->json([
                'status' => 'error',
                'message' => $firstErrorMessage, // Return only the first error message
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during login.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function uploadProfilePicture(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
        
            $user = $request->user();
            
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
        
            $path = $request->file('image')->store('profile-pictures', 'public');
            
            $user->update([
                'image' => $path
            ]);
        
            return response()->json([
                'status' => 'success',
                'message' => 'Profile picture uploaded successfully!',
                'image_url' => asset('storage/' . $path),
                'toast' => true
            ]);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $firstErrorKey = array_key_first($errors);
            $firstErrorMessage = $errors[$firstErrorKey][0];
        
            return response()->json([
                'status' => 'error',
                'message' => $firstErrorMessage,
                'toast' => true
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload profile picture. Please try again.',
                'error' => $e->getMessage(),
                'toast' => true
            ], 500);
        }
    }
}