<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Helpers\FrontHelper;
use App\Models\Produit;

class ProduitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sous-catégorie 1 : Fauteuils ergonomiques
        Produit::create([
            'name' => 'Fauteuil Ergonomique Confort+',
            'slug' => Str::slug('Fauteuil Ergonomique Confort+'),
            'price' => 499.99,
            'description' => 'Fauteuil ergonomique avec soutien lombaire réglable et accoudoirs ajustables.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p1.jpeg',
            'color' => 'Noir',
            'niveau_confort' => 'Élevé',
            'poids' => 15.50,
            'sous_categorie_id' => 1,
        ]);

        Produit::create([
            'name' => 'Fauteuil Ergonomique Dynamique',
            'slug' => Str::slug('Fauteuil Ergonomique Dynamique'),
            'price' => 399.99,
            'description' => 'Fauteuil avec mécanisme d\'inclinaison synchrone pour un confort optimal.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p2.jpeg',
            'color' => 'Gris',
            'niveau_confort' => 'Moyen',
            'poids' => 14.00,
            'sous_categorie_id' => 1,
        ]);

        // Sous-catégorie 2 : Fauteuils de direction
        Produit::create([
            'name' => 'Fauteuil de Direction Prestige',
            'slug' => Str::slug('Fauteuil de Direction Prestige'),
            'price' => 799.99,
            'description' => 'Fauteuil en cuir véritable avec dossier haut pour un look exécutif.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p3.png',
            'color' => 'Marron',
            'niveau_confort' => 'Élevé',
            'poids' => 20.00,
            'sous_categorie_id' => 2,
        ]);

        Produit::create([
            'name' => 'Fauteuil de Direction Moderne',
            'slug' => Str::slug('Fauteuil de Direction Moderne'),
            'price' => 649.99,
            'description' => 'Design épuré avec revêtement en similicuir et base en aluminium.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p4.jpg',
            'color' => 'Noir',
            'niveau_confort' => 'Élevé',
            'poids' => 18.50,
            'sous_categorie_id' => 2,
        ]);

        // Sous-catégorie 3 : Fauteuils visiteurs
        Produit::create([
            'name' => 'Fauteuil Visiteur Éco',
            'slug' => Str::slug('Fauteuil Visiteur Éco'),
            'price' => 149.99,
            'description' => 'Fauteuil simple et élégant pour salles d\'attente.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p5.jpg',
            'color' => 'Bleu',
            'niveau_confort' => 'Moyen',
            'poids' => 10.00,
            'sous_categorie_id' => 3,
        ]);

        Produit::create([
            'name' => 'Fauteuil Visiteur Confort',
            'slug' => Str::slug('Fauteuil Visiteur Confort'),
            'price' => 199.99,
            'description' => 'Fauteuil rembourré pour un meilleur confort des visiteurs.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Gris',
            'niveau_confort' => 'Élevé',
            'poids' => 12.00,
            'sous_categorie_id' => 3,
        ]);

        // Sous-catégorie 4 : Chaises de réunion
        Produit::create([
            'name' => 'Chaise de Réunion Standard',
            'slug' => Str::slug('Chaise de Réunion Standard'),
            'price' => 99.99,
            'description' => 'Chaise empilable pour salles de réunion.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Noir',
            'niveau_confort' => 'Moyen',
            'poids' => 8.00,
            'sous_categorie_id' => 4,
        ]);

        Produit::create([
            'name' => 'Chaise de Réunion Premium',
            'slug' => Str::slug('Chaise de Réunion Premium'),
            'price' => 149.99,
            'description' => 'Chaise avec dossier rembourré et structure chromée.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Bleu',
            'niveau_confort' => 'Élevé',
            'poids' => 9.50,
            'sous_categorie_id' => 4,
        ]);

        // Sous-catégorie 5 : Tabourets
        Produit::create([
            'name' => 'Tabouret Haut Design',
            'slug' => Str::slug('Tabouret Haut Design'),
            'price' => 89.99,
            'description' => 'Tabouret moderne pour espaces collaboratifs.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Blanc',
            'niveau_confort' => 'Moyen',
            'poids' => 6.00,
            'sous_categorie_id' => 5,
        ]);

        Produit::create([
            'name' => 'Tabouret Réglable',
            'slug' => Str::slug('Tabouret Réglable'),
            'price' => 119.99,
            'description' => 'Tabouret avec hauteur ajustable et repose-pieds.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Noir',
            'niveau_confort' => 'Moyen',
            'poids' => 7.50,
            'sous_categorie_id' => 5,
        ]);

        // Sous-catégorie 6 : Poufs et bancs
        Produit::create([
            'name' => 'Pouf Modulaire',
            'slug' => Str::slug('Pouf Modulaire'),
            'price' => 129.99,
            'description' => 'Pouf polyvalent pour espaces de détente.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Vert',
            'niveau_confort' => 'Élevé',
            'poids' => 5.00,
            'sous_categorie_id' => 6,
        ]);

        Produit::create([
            'name' => 'Banc d\'Accueil',
            'slug' => Str::slug('Banc d\'Accueil'),
            'price' => 249.99,
            'description' => 'Banc rembourré pour halls d\'entrée.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Gris',
            'niveau_confort' => 'Élevé',
            'poids' => 15.00,
            'sous_categorie_id' => 6,
        ]);

        // Sous-catégorie 7 : Bureaux individuels
        Produit::create([
            'name' => 'Bureau Individuel Compact',
            'slug' => Str::slug('Bureau Individuel Compact'),
            'price' => 299.99,
            'description' => 'Bureau compact idéal pour petits espaces.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Chêne',
            'niveau_confort' => null,
            'poids' => 25.00,
            'sous_categorie_id' => 7,
        ]);

        Produit::create([
            'name' => 'Bureau Individuel Exécutif',
            'slug' => Str::slug('Bureau Individuel Exécutif'),
            'price' => 499.99,
            'description' => 'Bureau spacieux avec finition haut de gamme.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Noyer',
            'niveau_confort' => null,
            'poids' => 35.00,
            'sous_categorie_id' => 7,
        ]);

        // Sous-catégorie 8 : Bureaux partagés (benchs)
        Produit::create([
            'name' => 'Bench Collaboratif 4 Places',
            'slug' => Str::slug('Bench Collaboratif 4 Places'),
            'price' => 799.99,
            'description' => 'Bureau partagé pour équipes de 4 personnes.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Blanc',
            'niveau_confort' => null,
            'poids' => 50.00,
            'sous_categorie_id' => 8,
        ]);

        Produit::create([
            'name' => 'Bench Modulaire 6 Places',
            'slug' => Str::slug('Bench Modulaire 6 Places'),
            'price' => 999.99,
            'description' => 'Bureau modulaire pour équipes dynamiques.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Gris',
            'niveau_confort' => null,
            'poids' => 60.00,
            'sous_categorie_id' => 8,
        ]);

        // Sous-catégorie 9 : Bureaux réglables en hauteur
        Produit::create([
            'name' => 'Bureau Réglable Électrique',
            'slug' => Str::slug('Bureau Réglable Électrique'),
            'price' => 649.99,
            'description' => 'Bureau avec réglage électrique pour position assise/debout.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Noir',
            'niveau_confort' => null,
            'poids' => 40.00,
            'sous_categorie_id' => 9,
        ]);

        Produit::create([
            'name' => 'Bureau Réglable Manuel',
            'slug' => Str::slug('Bureau Réglable Manuel'),
            'price' => 499.99,
            'description' => 'Bureau avec réglage manuel de la hauteur.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Blanc',
            'niveau_confort' => null,
            'poids' => 35.00,
            'sous_categorie_id' => 9,
        ]);

        // Sous-catégorie 10 : Tables de réunion
        Produit::create([
            'name' => 'Table de Réunion Ronde',
            'slug' => Str::slug('Table de Réunion Ronde'),
            'price' => 399.99,
            'description' => 'Table ronde pour réunions collaboratives.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Chêne',
            'niveau_confort' => null,
            'poids' => 30.00,
            'sous_categorie_id' => 10,
        ]);

        Produit::create([
            'name' => 'Table de Réunion Rectangulaire',
            'slug' => Str::slug('Table de Réunion Rectangulaire'),
            'price' => 599.99,
            'description' => 'Table spacieuse pour grandes réunions.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Noyer',
            'niveau_confort' => null,
            'poids' => 45.00,
            'sous_categorie_id' => 10,
        ]);

        // Sous-catégorie 11 : Tables basses
        Produit::create([
            'name' => 'Table Basse Design',
            'slug' => Str::slug('Table Basse Design'),
            'price' => 199.99,
            'description' => 'Table basse élégante pour espaces de détente.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Blanc',
            'niveau_confort' => null,
            'poids' => 15.00,
            'sous_categorie_id' => 11,
        ]);

        Produit::create([
            'name' => 'Table Basse Scandinave',
            'slug' => Str::slug('Table Basse Scandinave'),
            'price' => 249.99,
            'description' => 'Table basse en bois avec design nordique.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Chêne clair',
            'niveau_confort' => null,
            'poids' => 18.00,
            'sous_categorie_id' => 11,
        ]);

        // Sous-catégorie 12 : Tables pliantes
        Produit::create([
            'name' => 'Table Pliante Compacte',
            'slug' => Str::slug('Table Pliante Compacte'),
            'price' => 149.99,
            'description' => 'Table pliable pour espaces modulables.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Gris',
            'niveau_confort' => null,
            'poids' => 12.00,
            'sous_categorie_id' => 12,
        ]);

        Produit::create([
            'name' => 'Table Pliante Multi-Usages',
            'slug' => Str::slug('Table Pliante Multi-Usages'),
            'price' => 199.99,
            'description' => 'Table robuste pour événements et réunions.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Noir',
            'niveau_confort' => null,
            'poids' => 15.00,
            'sous_categorie_id' => 12,
        ]);

        // Sous-catégorie 13 : Cabines acoustiques
        Produit::create([
            'name' => 'Cabine Acoustique Solo',
            'slug' => Str::slug('Cabine Acoustique Solo'),
            'price' => 1999.99,
            'description' => 'Cabine individuelle pour concentration.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Bleu',
            'niveau_confort' => null,
            'poids' => 200.00,
            'sous_categorie_id' => 13,
        ]);

        Produit::create([
            'name' => 'Cabine Acoustique Duo',
            'slug' => Str::slug('Cabine Acoustique Duo'),
            'price' => 2999.99,
            'description' => 'Cabine pour deux personnes avec isolation sonore.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Gris',
            'niveau_confort' => null,
            'poids' => 250.00,
            'sous_categorie_id' => 13,
        ]);

        // Sous-catégorie 14 : Cabines téléphoniques
        Produit::create([
            'name' => 'Cabine Téléphonique Compacte',
            'slug' => Str::slug('Cabine Téléphonique Compacte'),
            'price' => 1499.99,
            'description' => 'Cabine pour appels privés en open space.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Noir',
            'niveau_confort' => null,
            'poids' => 150.00,
            'sous_categorie_id' => 14,
        ]);

        Produit::create([
            'name' => 'Cabine Téléphonique Confort',
            'slug' => Str::slug('Cabine Téléphonique Confort'),
            'price' => 1799.99,
            'description' => 'Cabine avec siège intégré pour appels prolongés.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Bleu',
            'niveau_confort' => null,
            'poids' => 170.00,
            'sous_categorie_id' => 14,
        ]);

        // Sous-catégorie 15 : Cabines de réunion
        Produit::create([
            'name' => 'Cabine de Réunion 4 Places',
            'slug' => Str::slug('Cabine de Réunion 4 Places'),
            'price' => 3999.99,
            'description' => 'Cabine pour petites réunions avec isolation acoustique.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Gris',
            'niveau_confort' => null,
            'poids' => 300.00,
            'sous_categorie_id' => 15,
        ]);

        Produit::create([
            'name' => 'Cabine de Réunion 6 Places',
            'slug' => Str::slug('Cabine de Réunion 6 Places'),
            'price' => 4999.99,
            'description' => 'Cabine spacieuse pour réunions d\'équipe.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Blanc',
            'niveau_confort' => null,
            'poids' => 350.00,
            'sous_categorie_id' => 15,
        ]);

        // Sous-catégorie 16 : Armoires
        Produit::create([
            'name' => 'Armoire de Rangement Haute',
            'slug' => Str::slug('Armoire de Rangement Haute'),
            'price' => 399.99,
            'description' => 'Armoire avec portes battantes pour stockage sécurisé.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Blanc',
            'niveau_confort' => null,
            'poids' => 50.00,
            'sous_categorie_id' => 16,
        ]);

        Produit::create([
            'name' => 'Armoire à Rideaux',
            'slug' => Str::slug('Armoire à Rideaux'),
            'price' => 349.99,
            'description' => 'Armoire avec rideaux coulissants pour gain de place.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Gris',
            'niveau_confort' => null,
            'poids' => 45.00,
            'sous_categorie_id' => 16,
        ]);

        // Sous-catégorie 17 : Étagères
        Produit::create([
            'name' => 'Étagère Modulaire',
            'slug' => Str::slug('Étagère Modulaire'),
            'price' => 149.99,
            'description' => 'Étagère configurable pour espaces de travail.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Chêne',
            'niveau_confort' => null,
            'poids' => 20.00,
            'sous_categorie_id' => 17,
        ]);

        Produit::create([
            'name' => 'Étagère Murale',
            'slug' => Str::slug('Étagère Murale'),
            'price' => 99.99,
            'description' => 'Étagère compacte pour rangement mural.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Blanc',
            'niveau_confort' => null,
            'poids' => 10.00,
            'sous_categorie_id' => 17,
        ]);

        // Sous-catégorie 18 : Supports et organisateurs
        Produit::create([
            'name' => 'Support pour Écran',
            'slug' => Str::slug('Support pour Écran'),
            'price' => 49.99,
            'description' => 'Support réglable pour écran d\'ordinateur.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Noir',
            'niveau_confort' => null,
            'poids' => 2.50,
            'sous_categorie_id' => 18,
        ]);

        Produit::create([
            'name' => 'Organisateur de Bureau',
            'slug' => Str::slug('Organisateur de Bureau'),
            'price' => 29.99,
            'description' => 'Organisateur pour stylos et petits accessoires.',
            'image_main' => FrontHelper::getEnvFolder() .'produits/p6.jpg',
            'color' => 'Gris',
            'niveau_confort' => null,
            'poids' => 1.00,
            'sous_categorie_id' => 18,
        ]);
    }
}
