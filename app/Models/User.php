<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = "users";

    protected $fillable = [
        'phone',
        'password',
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

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function getAllUsers()
    {
        return $this::latest()->paginate(1);
    }

    public function userCheck($phone)
    {
        return $this::where('phone', $phone)->first();
    }

    public function userSearch($keyword)
    {
        return $this::where('phone', 'like', '%' . $keyword . '%')
            ->orWhere('user_name', 'like', '%' . $keyword . '%')
            ->paginate(1);
    }

    public function getUserById($id)
    {
        return $this::find($id);
    }
}
