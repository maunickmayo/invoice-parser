<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\InvoiceParser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:parse')]
class ParseInvoicesCommand extends Command
{
    private InvoiceParser $parser;

    public function __construct(InvoiceParser $parser)
    {
        parent::__construct();
        $this->parser = $parser;
    }

    // Exécute la commande pour parser les fichiers de factures.
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            // Lancer le parsing des fichiers JSON et CSV situés dans le dossier data/.
            $this->parser->parse('data/invoices.json');
            $this->parser->parse('data/invoices.csv');

            // Affiche un message de succès dans la console.
            $output->writeln('Fichiers traités avec succès !');
            return Command::SUCCESS;
        } catch (\InvalidArgumentException $e) {
            // Capture et affiche les erreurs si le fichier n'est pas supporté.
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
