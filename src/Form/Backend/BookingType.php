<?php

namespace App\Form\Backend;

use App\Entity\Booking;
use App\Entity\Hotel;
use App\Entity\Suite;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('hotel', EntityType::class, [
                'label' => 'Hôtel associé',
                'class' => Hotel::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('h')
                        ->orderBy('h.name', 'ASC');
                },
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner l\'hôtel associé'])
                ]
            ])
            ->add('suite', EntityType::class, [
                'label' => 'Suite associé',
                'class' => Suite::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.title', 'ASC');
                },
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner la suite associée'])
                ]
            ])
            ->add('user', EntityType::class, [
                'label' => 'Client associé',
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.lastname', 'ASC');
                },
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner le client associé'])
                ]
            ])
            ->add('begin_at', DateType::class, [
                'label' => 'Date de début de séjour',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner la date de début de séjour'])
                ]
            ])
            ->add('end_at', DateType::class, [
                'label' => 'Date de fin de séjour',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner la date de fin de séjour'])
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'En attente de paiement' => Booking::STATUS_IN_PROGRESS,
                    'Paiement accepté' => Booking::STATUS_ACCEPTED,
                    'Paiement refusé' => Booking::STATUS_DECLINED,
                    'Terminée' => Booking::STATUS_DONE,
                    'Annulée' => Booking::STATUS_CANCELLED,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
