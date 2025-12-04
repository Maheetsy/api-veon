<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // We will use a generic User object or the existing model without saving to DB

class FirebaseTokenMiddleware
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        try {
            $userId = $this->firebaseService->verifyToken($token);

            if (!$userId) {
                return response()->json(['message' => 'Invalid token.'], 401);
            }

            $userData = $this->firebaseService->findUserById($userId);

            if (!$userData) {
                return response()->json(['message' => 'User not found.'], 401);
            }

            // Manually authenticate the user in Laravel for this request
            // We create a User instance but don't save it to SQL
            $user = new User();
            $user->id = $userData['id']; // This will be a string (Firestore ID)
            $user->name = $userData['name'];
            $user->email = $userData['email'];
            // Add other fields if necessary
            
            // Set the user in the request and Auth guard
            Auth::setUser($user);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });

        } catch (\Exception $e) {
            return response()->json(['message' => 'Authentication error: ' . $e->getMessage()], 500);
        }

        return $next($request);
    }
}
