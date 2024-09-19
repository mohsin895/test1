<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

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

    protected $with = ['media', 'specializations'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function doctor_details()
    {
        return $this->hasOne(DoctorDetails::class, 'user_id', 'id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media', 'id');
    }

    public function slots()
    {
        return $this->hasMany(DoctorChamber::class, 'doctor_chamber_id', 'id');
    }

    public function specializations()
    {
        return $this->hasMany(DoctorSpecialization::class, 'user_id', 'id');
    }

    public function designations()
    {
        return $this->hasMany(DoctorDesignations::class, 'user_id', 'id');
    }

    public function degrees()
    {
        return $this->hasMany(DoctorDegree::class, 'user_id', 'id');
    }

    public function doctor_chambers()
    {
        return $this->hasMany(DoctorChamber::class, 'user_id', 'id');
    }

    public function off_days()
    {
        return $this->hasMany(OffDay::class, 'doctor_id', 'id');
    }

    public function advertisings()
    {
        return $this->hasMany(DoctorAdvertise::class, 'doctor_id', 'id');
    }

    public function compounderDetails()
    {
        return $this->hasMany(CompounderDetails::class, 'compounder_id', 'id')
        ->select('compounder_id', 'chamber_id')
        ->distinct();
    }

}
