<?php

declare(strict_types=1);

namespace App\Service;


class FileExtension
{
    // Constantes des extensions de fichiers supportées
    public const SUPPORTED_EXTENSIONS = ['json', 'csv'];

    // Méthode pour vérifier si l'extension du fichier est supportée
    public static function isSupported(string $filePath): bool
    {
        // Récupère l'extension du fichier en minuscules
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        // Vérifie si l'extension est dans la liste des extensions supportées
        return in_array($extension, self::SUPPORTED_EXTENSIONS, true);
    }
}
