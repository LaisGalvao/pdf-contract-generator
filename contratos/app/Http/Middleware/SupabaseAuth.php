<?php
// app/Http/Middleware/SupabaseAuth.php
namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Profile;

class SupabaseAuth {
  public function handle(Request $request, Closure $next): Response {
    $auth = $request->bearerToken();
    if (!$auth) return response()->json(['message' => 'Unauthorized'], 401);

    try {
      $jwks = Cache::remember('supabase_jwks', 3600, function () {
        $url = config('services.supabase.jwks');
        return Http::get($url)->json();
      });
      $decoded = JWT::decode($auth, JWK::parseKeySet($jwks));
      $userId = $decoded->sub ?? null;
      $email  = $decoded->email ?? null;
      if (!$userId) return response()->json(['message' => 'Invalid token'], 401);

      // Upsert do profile
      $profile = Profile::query()->updateOrCreate(['id' => $userId], ['email' => $email ?? '']);
      // Disponibiliza no request
      $request->attributes->set('auth_user', $profile);
    } catch (\Throwable $e) {
      return response()->json(['message' => 'Invalid/expired token'], 401);
    }

    return $next($request);
  }
}
