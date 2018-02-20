<?php

namespace ColocationBundle\Form;

use ColocationBundle\Entity\Colocation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColocationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('loyer')
            ->add('titre')
            ->add('meuble', ChoiceType::class, array(
                'choices' => array(
                    'meublé' => 'meublé',
                    'non meublé' => 'non meublé',
                    'partiellement meublé' => 'partiellement meublé',
                )))
            ->add('type', ChoiceType::class, array(
                'choices' => array(
                    'maison' => 'maison',
                    'studio' => 'studio',
                )))
            ->add('description')
            ->add('ville', ChoiceType::class, array(
                'choices' => array(
                    'Tunis' => 'Tunis',
                    'Bizerte' => 'Bizerete',
                    'Monastir' => 'Monastir',
                    'Ben arous' => 'Ben arous',
                    'Gabes' => 'Gabes',
                    'Gafsa' => 'Gafsa',
                    'le Kef' => 'Le Kef',
                    'Mahdia' => 'Mahdia',
                    'Ariana' => 'Ariana',
                    'Tozeur' => 'Tozeur',
                    'Zaghouan' => 'Zaghouan',
                    'Béja' => 'Béja',
                    'Médenine' => 'Médenine',
                )))
            ->add('x',NumberType::class,array(
                'scale'=>15
            ))
            ->add('y',NumberType::class,array(
                'scale'=>15
            ))
            ->add('photo', FileType::class)
            ->add('photo1', FileType::class)
            ->add('photo2', FileType::class);


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Colocation::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'colocationbundle_colocation';
    }


}
