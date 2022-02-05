<?php

namespace App\Form;

use App\Entity\Band;
use App\Entity\Concert;
use App\Entity\Room;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConcertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class)
            ->add('date', DateType::class)
            ->add('begin', TimeType::class)
            ->add('end', TimeType::class)
            ->add('capacity', IntegerType::class, [
                "attr" => [
                    "min" => 0
                ]
            ])
            ->add('picture', TextType::class)
            ->add('room', EntityType::class, [
                "class" => Room::class,
                "choice_label" => "name"
            ])
            ->add('bands', EntityType::class, [
                "class" => Band::class,
                "choice_label" => "name",
                "multiple" => true,
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Concert::class,
            'csrf_protection' => false,
        ]);
    }
}
