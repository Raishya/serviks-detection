<?php
// User.php (App\Models\User)

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Attributes that are mass assignable
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'profile_photo',
    ];

    // Attributes that should be hidden for serialization
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casts for attributes
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Cast 'type' attribute to user roles
    protected function type(): Attribute
    {
        return new Attribute(
            get: fn ($value) =>  ["user", "dokter", "admin"][$value],
        );
    }

    // Check if the user has a specific role
    public function hasRole(string $role): bool
    {
        return $this->type === $role;
    }
}
