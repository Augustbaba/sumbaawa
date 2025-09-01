<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $table = 'produits';
    
    protected $fillable = [
        'name', 
        'slug', 
        'description', 
        'image_main', 
        'price', 
        'color', 
        'sous_categorie_id', 
        'niveau_confort', 
        'poids'
    ];

    public function sousCategorie()
    {
        return $this->belongsTo(SousCategorie::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}