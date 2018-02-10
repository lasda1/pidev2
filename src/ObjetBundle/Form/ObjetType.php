<?php

namespace ObjetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObjetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nature',ChoiceType::class,array('choices'=>array(
                'Objet Perdu'=> 'Objet Perdu',
                'Objet Trouvé'=> 'Objet Trouvé',
            )))
            ->add('description',TextareaType::class,array('attr'=>array('class' => 'form-control')))
            ->add('date',DateType::class,array('data'=>new \DateTime("now"))
            )
            ->add('type',ChoiceType::class,array('choices'=>array(
                'Telephone'=> 'Telephone',
                'Ordinateur'=> 'Ordinateur',
                'Chargeur'=> 'Chargeur',
                'Papier'=> 'Papier',
                'Autres'=> 'Autres',
            )))
            ->add('lieu',TextType::class)
            ->add('photo',FileType::class,array('required'=>false))
            ->add('Ajouter',SubmitType::class);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ObjetBundle\Entity\Objet'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'objetbundle_objet';
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }


}
