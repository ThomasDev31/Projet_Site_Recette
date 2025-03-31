<?php

namespace App\Form;

use App\Entity\Note;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', IntegerType::class, [
                'label' => 'Note de (0 à 5)',
                'required' => false,
                'constraints' => [
                    new Range(['min' => 1, 'max' => 5])
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'valide',
                'attr' => ['class' => "btn btn-lg btn-primary"]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
