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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
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
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('h')
                        ->orderBy('h.name', 'ASC');
                },
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner l\'hôtel pour lequel vous souhaitez réserver'])
                ]
            ]);

        $formModifier = function (FormInterface $form, Hotel $hotel = null) use ($options) {
            $suites = null === $hotel ? $options['suites'] : $hotel->getSuites();

            $form
                ->add('suite', EntityType::class, [
                    'label' => 'Suite associée',
                    'class' => Suite::class,
                    'choices' => $suites,
                    'required' => true,
                    'constraints' => [
                        new NotBlank(['message' => 'Veuillez sélectionner la suite que vous souhaitez réserver'])
                    ]
                ])
                ->add('beginAt', DateType::class, [
                    'label' => 'Date de début de séjour',
                    'input' => 'datetime_immutable',
                    'constraints' => [
                        new NotBlank(['message' => 'Veuillez sélectionner votre date de début de séjour souhaitée'])
                    ]
                ])
                ->add('endAt', DateType::class, [
                    'label' => 'Date de fin de séjour',
                    'input' => 'datetime_immutable',
                    'constraints' => [
                        new NotBlank(['message' => 'Veuillez sélectionner votre date de fin de séjour souhaitée'])
                    ]
                ]);
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($formModifier) {
            $data = $event->getData();
            $formModifier($event->getForm(), $data->getHotel());
        });

        $builder->get('hotel')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($formModifier) {
            $hotel = $event->getForm()->getData();
            $formModifier($event->getForm()->getParent(), $hotel);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'suites' => [],
        ]);
    }
}
