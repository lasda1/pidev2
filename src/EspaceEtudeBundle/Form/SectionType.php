<?php

namespace EspaceEtudeBundle\Form;

use EspaceEtudeBundle\Enum\Niveau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $niveau=new Niveau();
        $builder->
        add('libelle')
        ->add('niveau',ChoiceType::class,array(
            'choices'  =>$niveau->getAvailableTypes(),)

        );
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EspaceEtudeBundle\Entity\Section'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'espaceetudebundle_section';
    }


}
