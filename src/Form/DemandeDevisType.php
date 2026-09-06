<?php

namespace App\Form;

use App\Entity\DemandeDevis;
use App\Entity\Service;
use App\Repository\ServiceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\NotNull;

class DemandeDevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom complet',
                'attr' => ['autocomplete' => 'name'],
            ])
            ->add('societe', TextType::class, [
                'label' => 'Société',
                'required' => false,
                'attr' => ['autocomplete' => 'organization'],
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'attr' => ['autocomplete' => 'tel'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['autocomplete' => 'email'],
            ])
            ->add('adresseIntervention', TextType::class, [
                'label' => "Adresse d'intervention",
                'attr' => ['autocomplete' => 'street-address'],
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'label' => 'Type de prestation',
                'placeholder' => 'Sélectionner…',
                'constraints' => [new NotNull(message: 'Merci de choisir un type de prestation.')],
                'query_builder' => fn(ServiceRepository $repo) => $repo
                    ->createQueryBuilder('s')
                    ->andWhere('s.estActif = :actif')
                    ->setParameter('actif', true)
                    ->orderBy('s.position', 'ASC'),
            ])
            ->add('surface', IntegerType::class, [
                'label' => 'Surface approximative (m²)',
                'required' => false,
                'attr' => ['min' => 0, 'placeholder' => 'Ex. 250'],
                'help' => 'Facultatif — une estimation suffit',
                'help_attr' => ['class' => 'field-hint'],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'required' => false,
                'attr' => ['placeholder' => "Décrivez votre besoin, la fréquence souhaitée, un délai particulier…"],
            ])
            ->add('consentement', CheckboxType::class, [
                'label' => "J'accepte que mes informations soient utilisées pour être recontacté(e) dans le cadre de ma demande de devis.",
            ])
            // Piège à robots : invisible pour un humain, rempli par un bot et donc la soumission est refusée
            ->add('siteWeb', TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => false,
                'constraints' => [new Blank()],
                'attr' => [
                    'tabindex' => '-1',
                    'autocomplete' => 'off',
                    'aria-hidden' => 'true',
                ],
                'row_attr' => ['class' => 'hp-field'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandeDevis::class,
        ]);
    }
}