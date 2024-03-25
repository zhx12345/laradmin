<?php

namespace Zhxlan\Laradmin\Models\Sys;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * 管理员 MODEL
 * Adminusers class
 */
class SysUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'sys_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'avatar',
        'username',
        'email',
        'password',
        'status',
        'is_admin',
        'platform_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 关联角色
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hasRole()
    {
        return $this->hasOne(SysUserRole::class, 'user_id', 'id');
    }

    /**
     * 关联角色
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function roles()
    {
        return $this->hasManyThrough(
            SysRole::class,
            SysUserRole::class,
            'user_id',
            'id',
            'id',
            'role_id'
        );
    }

    /**
     * 为 array / JSON 序列化准备日期格式
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
