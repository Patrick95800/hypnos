<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Hotel;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir le nom'])
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir le prénom'])
                ]
            ])
            ->add('email', TextType::class, [
                'label' => 'Adresse email',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir l\'adresse email'])
                ]
            ])
            ->add('plainPassword', TextType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir le mot de passe'])
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles',
                'multiple' => true,
                'required' => false,
                'choices' => [
                    'Gérant' => User::ROLE_MANAGER,
                    'Administrateur' => User::ROLE_ADMIN,
                ]
            ])
            ->add('hotel', EntityType::class, [
                'label' => 'Hôtel géré',
                'class' => Hotel::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('h')
                        ->orderBy('h.name', 'ASC');
                },
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
