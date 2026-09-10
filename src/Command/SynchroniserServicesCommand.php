<?php

declare(strict_types=1);

namespace App\Command;

use App\Content\CatalogueServices;
use App\Entity\Service;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:services:synchroniser',
    description: 'Crée ou met à jour les services de référence à partir du catalogue.',
)]
final class SynchroniserServicesCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ServiceRepository $serviceRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'dry-run',
            null,
            InputOption::VALUE_NONE,
            'Affiche ce qui serait fait, sans rien écrire en base.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io         = new SymfonyStyle($input, $output);
        $simulation = (bool) $input->getOption('dry-run');

        if ($simulation) {
            $io->note('Mode simulation : aucune écriture ne sera effectuée.');
        }

        $lignes    = [];
        $crees     = 0;
        $misAJour  = 0;
        $inchanges = 0;

        foreach (CatalogueServices::all() as $definition) {
            $service = $this->serviceRepository->findOneBy(['slug' => $definition['slug']]);
            $nouveau = null === $service;

            if ($nouveau) {
                $service = new Service();
                $service->setSlug($definition['slug']);
                $this->entityManager->persist($service);
            }

            // Empreinte AVANT modification, pour détecter un changement réel.
            $avant = $nouveau ? null : $this->empreinte($service);

            $service
                ->setNom($definition['nom'])
                ->setDescription($definition['description'])
                ->setIcone($definition['icone'])
                ->setCategorie($definition['categorie'])
                ->setPosition($definition['position']);

            // "estActif" n'est volontairement PAS écrasé : voir l'explication.

            if ($nouveau) {
                $action = 'créé';
                ++$crees;
            } elseif ($avant !== $this->empreinte($service)) {
                $action = 'mis à jour';
                ++$misAJour;
            } else {
                $action = '—';
                ++$inchanges;
            }

            $lignes[] = [$definition['slug'], $action];
        }

        if ($simulation) {
            // On abandonne toutes les modifications en mémoire.
            $this->entityManager->clear();
        } else {
            $this->entityManager->flush();
        }

        $io->table(['Slug', 'Action'], $lignes);
        $io->success(sprintf(
            '%d créé(s), %d mis à jour, %d inchangé(s).',
            $crees,
            $misAJour,
            $inchanges
        ));

        return Command::SUCCESS;
    }

    /**
     * Empreinte des champs pilotés par le catalogue.
     *
     * @return array{string|null, string|null, string|null, \App\Enum\CategorieService|null, int}
     */
    private function empreinte(Service $service): array
    {
        return [
            $service->getNom(),
            $service->getDescription(),
            $service->getIcone(),
            $service->getCategorie(),
            $service->getPosition(),
        ];
    }
}