<?php

declare(strict_types=1);


namespace App\Repository;

use App\Entity\Invoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    // On pourrait ajouter ici des méthodes spécifiques pour récupérer ou filtrer des factures,
    // afin d'éviter de surcharger les services ou les contrôleurs.
}
