<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'type_id', 'code', 'address', 'status', 'payment_method', 'payment_status', 'payment_id', 'payment_email', 'total_amount', 'delivery_method', 'delivery_info', 'estimated_delivery', 'shipping_status', 'shipping_fee', 'observations', 'shipping_payment_id', 'shipping_payment_date', 'is_received', 'received_at', 'shipping_payment_method'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    // Relation avec les produits commandés (via la table commander)
    public function commandesProduits()
    {
        return $this->hasMany(Commander::class, 'commande_id');
    }

    // Alias pour commander (si vous préférez ce nom)
    public function commander()
    {
        return $this->hasMany(Commander::class, 'commande_id');
    }

    // Relation avec les produits (à travers commander)
    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'commander', 'commande_id', 'produit_id')
                    ->withPivot('quantity', 'description_produit', 'unit_price', 'total_price')
                    ->withTimestamps();
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'En attente',
            'processing' => 'En cours de traitement',
            'ready_pickup' => 'Prêt pour retrait',
            'picked_up' => 'Récupéré par le cargo',
            'shipped' => 'Expédié',
            'in_transit' => 'En cours de livraison',
            'arrived' => 'Arrivé à destination',
            'delivered' => 'Livré',
            'cancelled' => 'Annulé'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    // Accessor pour le label de la méthode de livraison
    public function getDeliveryMethodLabelAttribute()
    {
        $labels = [
            'tinda_awa' => 'Tinda Awa',
            'cargo' => 'Cargo'
        ];

        return $labels[$this->delivery_method] ?? $this->delivery_method;
    }

    // Accessor pour le total avec frais de livraison
    public function getTotalWithShippingAttribute()
    {
        return $this->total_amount + ($this->shipping_fee ?? 0);
    }

    public function getShippingStatusLabelAttribute()
    {
        $labels = [
            'fee_pending' => 'En attente de paiement',
            'fee_paid' => 'Payé',
            null => 'Non défini'
        ];

        return $labels[$this->shipping_status] ?? $this->shipping_status;
    }
}
