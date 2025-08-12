<?php // routes/breadcrumbs.php

use App\Models\SomeModel;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Tableau de bord
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Tableau de bord', route('dashboard'));
});

Breadcrumbs::macro('resource', function (string $name, string $title) {
    // Tableau de bord > [Name]
    Breadcrumbs::for("{$name}.index", function (BreadcrumbTrail $trail) use ($name, $title) {
        $trail->parent('dashboard');
        $trail->push($title, route("{$name}.index"));
    });



    // Tableau de bord > [Name] > New
    Breadcrumbs::for("{$name}.create", function (BreadcrumbTrail $trail) use ($name) {
        $trail->parent("{$name}.index");
        $trail->push('Ajouter', route("{$name}.create"));
    });

    // Tableau de bord > [Name] > Name 123
    Breadcrumbs::for("{$name}.show", function (BreadcrumbTrail $trail, $title) use ($name) {
        $trail->parent("{$name}.index");
        $trail->push($title, route("{$name}.show", $title));
    });

    // Tableau de bord > [Name] > Name 123 > Edit
    Breadcrumbs::for("{$name}.edit", function (BreadcrumbTrail $trail, $title) use ($name) {
        $trail->parent("{$name}.show", $title);
        $trail->push('Modifier', route("{$name}.edit", $title));
    });

    // Tableau de bord > [Name] > Name 123 > Start
    Breadcrumbs::for("{$name}.start", function (BreadcrumbTrail $trail, $title) use ($name) {
        $trail->parent("{$name}.show", $title);
        $trail->push('Démarrer', route("{$name}.start", $title));
    });

    Breadcrumbs::for("{$name}.carry", function (BreadcrumbTrail $trail, $title) use ($name) {
        $trail->parent("{$name}.show", $title);
        $trail->push('Reporter', route("{$name}.carry", $title));
    });

    // Breadcrumbs::for('profile.edit', function (BreadcrumbTrail $trail) {
    //     $trail->push('Profil', route('profile.edit'));
    // });


});

Breadcrumbs::resource('projects', 'Projets');
Breadcrumbs::resource('countries', 'Pays');
Breadcrumbs::resource('domains', 'Domaines');
Breadcrumbs::resource('typeContributions', 'TypeContribution');
Breadcrumbs::resource('categories', 'Catégories');
Breadcrumbs::resource('partners', 'Partenaires');
Breadcrumbs::resource('actualities', 'Actualités');
Breadcrumbs::resource('type', 'Types');
Breadcrumbs::resource('financingMethods', 'MéthodeFinancement');
Breadcrumbs::resource('formations', 'Formations');
Breadcrumbs::resource('users', 'Utilisateurs');
Breadcrumbs::resource('consultations', 'Consultations');
Breadcrumbs::resource('settings', 'Paramètres');
Breadcrumbs::resource('attendances', 'Historiques');
Breadcrumbs::resource('messages', 'Messages');
Breadcrumbs::resource('refunds', 'Remboursements');

