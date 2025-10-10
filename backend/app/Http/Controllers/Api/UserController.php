<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $users = User::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->string('search') . '%')
                      ->orWhere('email', 'like', '%' . $request->string('search') . '%');
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->where('role', $request->string('role'));
            })
            ->with(['permissions'])
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15));

        return response()->json($users);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,user',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Gán permissions nếu có
        if (isset($validated['permissions'])) {
            $user->permissions()->sync($validated['permissions']);
        }

        $user->load(['permissions']);

        return response()->json($user, 201);
    }

    public function show(User $user): JsonResponse
    {
        $user->load(['permissions']);
        return response()->json($user);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:super_admin,user',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        // Chỉ cập nhật password nếu được cung cấp
        if (isset($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Cập nhật permissions nếu có
        if (isset($validated['permissions'])) {
            $user->permissions()->sync($validated['permissions']);
        }

        $user->load(['permissions']);

        return response()->json($user);
    }

    public function destroy(User $user): JsonResponse
    {
        // Không cho phép xóa chính mình
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'Cannot delete your own account'
            ], 422);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function updatePermissions(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $user->permissions()->sync($validated['permissions']);
        $user->load(['permissions']);

        return response()->json([
            'message' => 'Permissions updated successfully',
            'user' => $user
        ]);
    }

    public function getPermissions(): JsonResponse
    {
        $permissions = Permission::orderBy('group')->orderBy('name')->get();
        return response()->json($permissions);
    }

    public function getRoles(): JsonResponse
    {
        $roles = [
            ['value' => 'super_admin', 'label' => 'Super Admin'],
            ['value' => 'user', 'label' => 'User'],
        ];

        return response()->json($roles);
    }

    public function changePassword(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 422);
        }

        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return response()->json(['message' => 'Password changed successfully']);
    }

    public function getProfile(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load(['permissions']);
        
        return response()->json($user);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);
        $user->load(['permissions']);

        return response()->json($user);
    }
}