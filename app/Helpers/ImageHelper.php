<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use File;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    function redimensionnerEtEnregistrerImage($fichierImage, $repertoireCible, $largeur, $hauteur)
    {
        $image = Image::make($fichierImage);

        $imageRedimensionnee = $image->resize($largeur, $hauteur);

        $nomFichier = uniqid() . '.' . $fichierImage->getClientOriginalExtension();

        $cheminCible = $repertoireCible;

        if (!file_exists($cheminCible)) {
            mkdir($cheminCible, 0755, true);
        }

        $cheminCible .= '/' . $nomFichier;

        $imageRedimensionnee->save(public_path($cheminCible));

        return $cheminCible;
    }

    function enregistrerImage($fichierImage, $repertoireCible)
    {
        $nomFichier = uniqid() . '.' . $fichierImage->getClientOriginalExtension();

        $cheminCible = $repertoireCible . '/' . $nomFichier;

        if (!file_exists($repertoireCible)) {
            mkdir($repertoireCible, 0755, true);
        }

        $fichierImage->move($repertoireCible, $nomFichier);

        return $cheminCible;
    }


    function logo_favicon($fichierImage, $repertoireCible, $nomFichier, $largeur, $hauteur)
    {
        $cheminCible = $repertoireCible . '/' . $nomFichier;

        if (Storage::exists($cheminCible)) {
            Storage::delete($cheminCible);
        }

        $image = Image::make($fichierImage);

        $image->resize($largeur, $hauteur, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $image->save(public_path($cheminCible));

        return $cheminCible;
    }


    function removeImage($cheminImage)
    {
        if (file_exists($cheminImage)) {
            unlink($cheminImage);
            return true;
        }

        return false;
    }
}
