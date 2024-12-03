<?php

use App\Models\Policy;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

function isNavbarActive(string $url): string
{
    return Request()->is($url) ? 'active' : '';
}
function isNavbarTreeActive(string $url): string
{
    return Request()->is(app()->getLocale().'/'.$url) ? 'is-expanded' : '';
}
function isFullUrl(string $url): string
{
    return Request()->fullUrl() == url(app()->getLocale().'/'.$url) ? 'active' : '';
}
function getAuthByGuard(string $guard): Authenticatable
{
    return auth()->guard($guard)->user();
}
function hasRole($roleName): bool
{
    $user = Auth::user();
    if ($user->id == 1 && $roleName != 'normal') {
        return true;
    }
    // Ensure user is authenticated and has a role
    if ($user && $user->role) {
        return $user->role->name === $roleName;
    }

    return false;
}
function hasPermission($permissionName)
{
    $user = Auth::user();
    if ($user->id == 1) {
        return true;
    }

    // Ensure user is authenticated and has permissions through their role
    if ($user && $user->role) {
        return $user->role->permissions()->where('perm_name', $permissionName)->exists();
    }

    return false;
}

 function getTodayPolicies()
{
    return Policy::where('type', 'policy')
        ->whereDate('created_at', Carbon::today())
        ->get();
}
 function getTodayStandards()
{
   return Policy::where('type', 'standard')
        ->whereDate('created_at', Carbon::today())
        ->get();
}
