<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// plugin:Spatie
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    // plugin:Spatie
    use HasRoles;

    const GENDERS = ['Male','Female','Transgender','Gender Neutral','Others'];
    const RELATIONSHIP_STATUSES = ['Single','In a Relationship','Engaged','Married','It is Complicated','In an Open Relationship','Widowed','Separated','Divorced'];
    const PROFESSIONAL_STATUSES = ['Register','Pending'];
    const PROFESSIONAL_STATUSES_ADMINISTRATOR = ['Approve','Disapprove'];
    const PROFESSINAL_STATUS_REGISTER = 'Register';
    const PROFESSINAL_STATUS_PENDING = 'Pending';
    const PROFESSIONAL_STATUS_APPROVE = 'Approve';
    const PROFESSIONAL_STATUS_DISAPPROVE = 'Disapprove';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'birthday',
        'gender',
        'relationship_status',
        'contact_number',
        'image',
        'professional_title',
        'professional_description',
        'professional_status',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'professional_status' => 'Register',
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
}
