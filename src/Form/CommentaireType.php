<?php
namespace App\Form;

use App\Entity\Message;
use App\Entity\Note;
use App\Form\NoteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentaires', TextType::class, [
                'label' => 'Votre commentaire',
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Valider'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class, 
        ]);
    }
}
