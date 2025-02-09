<?php

declare(strict_types=1);

namespace App\Tests;

use App\Service\InvoiceParser;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InvoiceParserTest extends KernelTestCase
{
    //Ici on ajouté un commentaire PHPDoc avant la déclaration de la propriété $entityManager pour indiquer qu'elle est une instance de EntityManagerInterface:

    /** @var EntityManagerInterface&\PHPUnit\Framework\MockObject\MockObject */
    private $entityManager;

    public function testParseJsonFile(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $connection = $this->createMock(Connection::class);
        $this->entityManager->method('getConnection')->willReturn($connection);

        $connection->expects($this->exactly(10))->method('executeStatement');

        $invoiceParser = new InvoiceParser($this->entityManager);

        $invoiceParser->parse('data/invoices.json');
    }

    public function testParseCsvFile(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $connection = $this->createMock(Connection::class);
        $this->entityManager->method('getConnection')->willReturn($connection);

        $connection->expects($this->exactly(10))->method('executeStatement');

        $invoiceParser = new InvoiceParser($this->entityManager);

        $invoiceParser->parse('data/invoices.csv');
    }
}
