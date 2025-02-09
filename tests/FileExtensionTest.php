<?php

declare(strict_types=1);

namespace App\Tests;

use App\Service\FileExtension;
use PHPUnit\Framework\TestCase;

class FileExtensionTest extends TestCase
{
    public function testIsSupportedWithValidExtensions(): void
    {
        // Vérification des extensions valides
        $this->assertTrue(FileExtension::isSupported('document.json'));
        $this->assertTrue(FileExtension::isSupported('data.csv'));
    }

    public function testIsSupportedWithInvalidExtension(): void
    {
        // Vérification des extensions non supportées
        $this->assertFalse(FileExtension::isSupported('image.jpg'));
        $this->assertFalse(FileExtension::isSupported('file.txt'));
    }

    public function testIsSupportedWithNoExtension(): void
    {
        // Vérification  sans extension
        $this->assertFalse(FileExtension::isSupported('document'));
    }

    public function testIsSupportedWithDifferentCase(): void
    {
        // Vérification que la méthode est insensible à la casse
        $this->assertTrue(FileExtension::isSupported('document.JSON'));
        $this->assertTrue(FileExtension::isSupported('data.CSV'));
        $this->assertFalse(FileExtension::isSupported('document.TXT'));
    }

    public function testIsSupportedWithComplexFileNames(): void
    {
        // Test de fichiers avec des noms complexes
        $this->assertTrue(FileExtension::isSupported('nested/dir/file.document.JSON'));
        $this->assertTrue(FileExtension::isSupported('archive/data.somefile.csv'));
    }
}
