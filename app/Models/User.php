<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    public function hasPermission($permission)
    {
        // Super admin có tất cả quyền
        if ($this->role === 'super_admin') {
            return true;
        }
        
        // Kiểm tra quyền được phân cụ thể
        if ($this->permissions()->where('name', $permission)->exists()) {
            return true;
        }
        
        // Admin có quyền cơ bản mặc định (nếu chưa được phân quyền cụ thể)
        if ($this->role === 'admin' && $this->permissions()->count() === 0) {
            $defaultAdminPermissions = [
                'truy-cap-he-thong', 'xem-danh-sach-sinh-vien', 'xem-danh-sach-khoa', 
                'xem-danh-sach-lop', 'xem-danh-sach-mon-hoc', 'xem-danh-sach-thanh-vien'
            ];
            
            if (in_array($permission, $defaultAdminPermissions)) {
                return true;
            }
        }
        
        return false;
    }

    public function assignPermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }
        
        if ($permission) {
            $this->permissions()->syncWithoutDetaching([$permission->id]);
        }
    }

    public function removePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }
        
        if ($permission) {
            $this->permissions()->detach($permission->id);
        }
    }
}
