<?php

namespace App\Form\Backend;

use App\Entity\Hotel;
use App\Entity\Suite;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SuiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir le nom'])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir la description'])
                ]
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Montant par nuit (exprimé en cts)',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir le montant par nuit'])
                ]
            ])
            ->add('bookingLink', TextType::class, [
                'label' => 'Lien vers la page booking.com',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir le lien booking.com'])
                ]
            ])
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
            ->add('featuredImage', FileType::class,[
                'label' => false,
                'multiple' => false,
                'mapped' => false,
                'required' => false
            ])
            ->add('images', FileType::class,[
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Suite::class,
        ]);
    }
}
