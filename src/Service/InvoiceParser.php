<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\FileExtension;

class InvoiceParser
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    //Analyse un fichier de factures et met à jour les données en base. string $filePath Chemin du fichier JSON.
    public function parse(string $filePath): void
    {
        // Vérifie si le fichier a une extension supportée avant de le traiter.
        if (!FileExtension::isSupported($filePath)) {
            throw new \InvalidArgumentException("Extension de fichier non prise en charge.");
        }

        // Détermine le type de fichier et appelle la méthode de parsing appropriée.
        if (str_ends_with($filePath, '.json')) {
            $this->parseJsonFile($filePath);
        } elseif (str_ends_with($filePath, '.csv')) {
            $this->parseCsvFile($filePath);
        }
    }

    //Analyse un fichier JSON et met à jour les factures.
    private function parseJsonFile(string $filePath): void
    {
        $fileContent = file_get_contents($filePath);
        $lines = preg_split("/\r\n|\n|\r/", $fileContent);

        $amount = "";
        $name = "";

        // Parcourt chaque ligne du fichier JSON pour extraire les valeurs nécessaires.
        foreach ($lines as $line) {
            if (str_contains($line, '"montant"')) {
                $amount = $this->extractValue($line);
            }
            if (str_contains($line, '"nom"')) {
                $name = $this->extractValue($line);
            }
            // Dès qu'une facture est entièrement lue, on met à jour la base.
            if (str_contains($line, "}")) {
                $this->updateInvoice($amount, $name);
            }
        }
    }

    private function parseCsvFile(string $filePath): void
    {
        // Charge le fichier CSV et transforme chaque ligne en tableau.
        $data = array_map(fn($row) => str_getcsv($row, "\t", '"', "\\"), file($filePath));

        // Parcourt chaque ligne du fichier et met à jour la base de données.
        foreach ($data as $row) {
            if (!empty($row[0]) && !empty($row[2])) {
                $this->updateInvoice($row[0], $row[2]);
            }
        }
    }

    //Extrait la valeur d'une ligne JSON au format "clé: valeur".
    private function extractValue(string $line): string
    {
        $parts = explode(": ", $line, 2);
        return isset($parts[1]) ? trim($parts[1], ",\" ") : "";
    }

    // Met à jour une facture dans la base de données.
    private function updateInvoice(string $amount, string $name): void
    {
        $this->em->getConnection()->executeStatement(
            "UPDATE invoice SET amount = :amount WHERE name = :name",
            ['amount' => $amount, 'name' => $name]
        );
    }
}
