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
            ->add('description',TextareaType::class,array('attr'=>array('class' => 'form-control')))
            ->add('date',DateType::class,array('data'=>new \DateTime("now"))
            )
            ->add('type',ChoiceType::class,array('choices'=>array(
                'Telephone'=> 'Telephone',
                'Ordinateur'=> 'Ordinateur',
                'Chargeur'=> 'Chargeur',
                'Papier'=> 'Papier',
                'CIN'=>'CIN',
                'Autres'=> 'Autres',
            )))
            ->add('lieu',TextType::class)
            ->add('photo',FileType::class,array('attr' => array('class' => 'form-control file'),'data_class' => null,'label'=>'Photo'))
            ->add('Ajouter',SubmitType::class,array('attr' => array('class' => 'btn btn-primary')));

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


}
