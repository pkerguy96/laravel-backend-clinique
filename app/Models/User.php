<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use App\Models\WaitingRoom;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,  HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];


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
        'password' => 'hashed',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }
    public function Ordonance()
    {
        return $this->hasMany(Ordonance::class, 'doctor_id');
    }
    public function  OperationPref()
    {
        return $this->hasMany(OperationPreference::class, 'doctor_id');
    }
    public function payments()
    {
        return $this->hasManyThrough(
            Payement::class,
            Operation::class,
            'doctor_id', // Foreign key on operations table
            'operation_id', // Foreign key on payments table
            'id', // Local key on doctors table
            'id' // Local key on operations table
        );
    }
    /*  protected static function boot()
    {
        parent::boot();

        // Listen for the creating event to assign roles
        static::creating(function ($user) {
            if ($user->role === 'doctor') {
                $user->assignRole('doctor');
            }
        });
    } */

    protected static function boot()
    {
        parent::boot();


        static::created(function ($user) {
            // Create a new user preference row when a user is created
            UserPreference::create([
                'doctor_id' => $user->id,
                'kpi_date' => 'year',
            ]);
            if ($user->role === 'doctor') {
                $superAdminRole =   Role::create(['name' => 'Super-Admin', 'guard_name' => 'sanctum', 'team_id' => $user->id]);
                $permissions = Permission::pluck('id')->toArray();
                $superAdminRole->permissions()->sync($permissions);
                WaitingRoom::create([
                    'doctor_id' => $user->id,
                    'num_patients_waiting' => 0,
                ]);
            }
        });
    }
}
