<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Band;
use App\Entity\Concert;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class)
            ->add('artists',EntityType::class, [
                "class" => Artist::class,
                "choice_label" => function(Artist $artist) {
                    return $artist->getFirstName() . " " . $artist->getSurname();
                },
                "multiple" => true
            ])
            ->add('concerts',EntityType::class, [
                "class" => Concert::class,
                "choice_label" => "name",
                "multiple" => true,
                "required" => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Band::class,
            "csrf_protection" => false
        ]);
    }
}
