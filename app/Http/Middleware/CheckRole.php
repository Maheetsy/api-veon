<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Services\FirebaseService;
class CheckRole
{
    protected $firebaseService;
    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['error' => 'Unauthorized - No token provided'], 401);
        }
        $uid = $this->firebaseService->verifyToken($token);
        if (!$uid) {
            return response()->json(['error' => 'Unauthorized - Invalid token'], 401);
        }
        // Obtener usuario para verificar rol
        $user = $this->firebaseService->findUserById($uid);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        // Si el usuario es admin, tiene acceso a todo
        if (isset($user['role']) && $user['role'] === 'admin') {
            return $next($request);
        }
        // Verificar si el rol del usuario está en los roles permitidos para esta ruta
        if (isset($user['role']) && in_array($user['role'], $roles)) {
            return $next($request);
        }
        return response()->json(['error' => 'Forbidden - Insufficient permissions'], 403);
    }
}