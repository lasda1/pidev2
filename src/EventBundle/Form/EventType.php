<?php

namespace EventBundle\Form;

use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class)
            ->add('description',TextareaType::class)
            ->add('datedebut',DateType::class,array(
                'widget' => 'single_text'
            ))
            ->add('datefin',DateType::class,array(
                'widget' => 'single_text',

            ))

            ->add('lieu',TextType::class)
            ->add('x',NumberType::class,array(
                'scale'=>15
            ))
            ->add('y',NumberType::class,array(
                'scale'=>15
            ))
            ->add('photo',TextType::class)
            ->add('nbMax',NumberType::class)

            ->add('categorie',ChoiceType::class,array(
                'choices'=>array(
                    'Randonnée'=>'Randonnée',
                    'Workshop'=>'Workshop',
                    'Camping'=>'Camping',
                    'Formation'=>'Formation',
                    'Anniversaire Club'=>'Annivaisaire Club'
                )
            ))
            ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EventBundle\Entity\Event'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'eventbundle_event';
    }


}
