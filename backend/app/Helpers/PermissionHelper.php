<?php

namespace App\Helpers;

class PermissionHelper
{
    public static function canEdit($user)
    {
        return in_array($user->role, ['super_admin', 'admin']);
    }
    
    public static function canDelete($user)
    {
        return $user->role === 'super_admin';
    }
    
    public static function canCreate($user)
    {
        return $user->role === 'super_admin';
    }
    
    public static function canView($user)
    {
        return in_array($user->role, ['super_admin', 'admin', 'user']);
    }
}


