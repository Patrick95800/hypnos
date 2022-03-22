<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Hotel;
use App\Entity\Suite;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
                    new NotBlank(['message' => 'Veuillez sélectionner l\'hôtel pour lequel vous souhaitez réserver'])
                ]
            ])
            ->add('suite', EntityType::class, [
                'label' => 'Suite associée',
                'class' => Suite::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.title', 'ASC');
                },
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner la suite que vous souhaitez réserver'])
                ]
            ])
            ->add('begin_at', DateType::class, [
                'label' => 'Date de début de séjour',
                'input' => 'datetime_immutable',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner votre date de début de séjour souhaitée'])
                ]
            ])
            ->add('end_at', DateType::class, [
                'label' => 'Date de fin de séjour',
                'input' => 'datetime_immutable',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner votre date de fin de séjour souhaitée'])
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
