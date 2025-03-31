<?php

namespace App\Form;

use App\Entity\Recette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('content')
            ->add('duration')
            ->add('littleDescription')
            ->add('file', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false

            ])
            ->add('createAt', null, [
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
