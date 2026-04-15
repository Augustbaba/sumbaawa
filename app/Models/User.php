<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified',
        'phone',
        'address',
        'avatar',
        'status',
        'google_id',
        'deleted_at',
        'preferred_currency_id',
        'email_verified',
        'solde',
    ];

    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }

    public function getStatusLabelAttribute()
    {
        return $this->status ?
            '<span class="badge bg-success">Actif</span>' :
            '<span class="badge bg-danger">Inactif</span>';
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'preferred_currency_id', 'id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    /**
     * Relation avec les notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Récupérer les notifications non lues
     */
    public function unreadNotifications()
    {
        return $this->notifications()->unread()->recent();
    }

    /**
     * Récupérer les notifications lues
     */
    public function readNotifications()
    {
        return $this->notifications()->read()->recent();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    // transactions traitées par l'admin
    public function adminTransactions()
    {
        return $this->hasMany(Transaction::class, 'admin_id');
    }

    /**
     * Compter les notifications non lues
     */
    public function unreadNotificationsCount()
    {
        return $this->notifications()->unread()->count();
    }

    /**
     * Créer une notification
     */
    public function notifie($type, $title, $message, $data = null)
    {
        return $this->notifications()->create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'is_read' => false
        ]);
    }

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
        'solde' => 'float',
    ];
}
