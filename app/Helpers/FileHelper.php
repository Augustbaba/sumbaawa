<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public function saveDocumentWithSlugName($firstName, $lastName, $documentFile)
    {
        $slugFirstName = Str::slug($firstName);
        $slugLastName = Str::slug($lastName);

        $extension = $documentFile->getClientOriginalExtension();
        $fileName = $slugFirstName . '-' . $slugLastName . '.' . $extension;

        $storageDirectory = $this->getStorageDirectory($extension);

        $documentFile->storeAs($storageDirectory, $fileName, 'public');

        return $fileName;
    }

    private function getStorageDirectory($extension)
    {
        switch ($extension) {
            case 'pdf':
                return 'pdfs';
            case 'doc':
            case 'docx':
                return 'pdfs';
            default:
                return 'other';
        }
    }
}