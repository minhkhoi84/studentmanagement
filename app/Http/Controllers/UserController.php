<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();
        
        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }
        
        if ($request->filled('role')) {
            $query->where('role', $request->string('role'));
        }
        
        $users = $query->orderBy('name')->paginate(10)->withQueryString();
        
        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin,user',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);
        
        return redirect()->route('users.index')->with('success', 'Thành viên đã được tạo thành công');
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:super_admin,admin,user',
            'password' => 'nullable|string|min:8|confirmed',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);
        
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $user->update($validated);
        
        // Cập nhật permissions nếu có
        if (isset($validated['permissions'])) {
            $user->permissions()->sync($validated['permissions']);
        } else {
            $user->permissions()->detach();
        }
        
        return redirect()->route('users.index')->with('success', 'Thành viên đã được cập nhật thành công');
    }

    public function destroy(User $user): RedirectResponse
    {
        // Không cho xóa chính mình
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Không thể xóa tài khoản của chính mình');
        }
        
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Thành viên đã được xóa thành công');
    }
}
