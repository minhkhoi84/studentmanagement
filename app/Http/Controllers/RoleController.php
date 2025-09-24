<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $users = User::with('permissions')->orderBy('name')->get();
        $permissions = Permission::orderBy('group')->orderBy('display_name')->get();
        $groupedPermissions = $permissions->groupBy('group');
        
        return view('roles.index', compact('users', 'groupedPermissions'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);
        
        // Chỉ super admin hoặc admin mới được phân quyền
        if (auth()->user()->role !== 'super_admin' && auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Bạn không có quyền phân quyền');
        }
        
        // Admin không được phân quyền cho super admin
        if (auth()->user()->role === 'admin' && $user->role === 'super_admin') {
            return redirect()->back()->with('error', 'Admin không thể phân quyền cho Super Admin');
        }
        
        $permissionIds = $validated['permissions'] ?? [];
        $user->permissions()->sync($permissionIds);
        
        return redirect()->route('roles.index')->with('success', 'Đã cập nhật quyền cho ' . $user->name);
    }
}
