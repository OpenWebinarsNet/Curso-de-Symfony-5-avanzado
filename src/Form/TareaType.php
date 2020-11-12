<?php

namespace App\Form;

use App\Entity\Tarea;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TareaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion', TextType::class, [
                'constraints' => [
                   // new NotBlank(['message' => 'Campo obligatorio'])
                ],
            ])
            ->add('finalizada', CheckboxType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tarea::class,
        ]);
    }
}
