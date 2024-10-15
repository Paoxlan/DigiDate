<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $roles = $this->getRoles($roles);
        $user = $request->user('web');

        if (is_null($user) || !in_array($user->role, $roles))
            abort(401);

        return $next($request);
    }

    public function getRoles(array $roles): array
    {
        return array_map($this->getRole(...), $roles);
    }

    /**
     * @throws Exception
     */
    public function getRole(string $name): array
    {
        if (!defined($qualified = Role::class . '::' . ucfirst($name)))
            throw new Exception(sprintf("Enum [%s] does not have case \"%s\"",
                Role::class, ucfirst($name)
            ));

        return constant($qualified);
    }
}
