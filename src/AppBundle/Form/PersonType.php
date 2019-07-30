<?php

namespace AppBundle\Form;

use AppBundle\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * PersonType form.
 */
class PersonType extends AbstractType
{
    /**
     * Add form fields to $builder.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        $builder->add('fullName', null, array(
            'label' => 'Full Name',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                $builder->add('sortableName', null, array(
            'label' => 'Sortable Name',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('birthDate', TextType::class, array(
            'label' => 'Birth date',
            'required' => false,
            'attr' => array(
                'help_block' => 'A four digit year, if known for certain. Uncertain date ranges (1901-1903) and circa dates (c1902) are supported here',
            ),
        ));
        $builder->add('deathDate', TextType::class, array(
            'label' => 'Birth date',
            'required' => false,
            'attr' => array(
                'help_block' => 'A four digit year, if known for certain. Uncertain date ranges (1901-1903) and circa dates (c1902) are supported here',
            ),
        ));
        $builder->add('birthPlace', Select2EntityType::class, array(
            'label' => 'Place',
            'multiple' => false,
            'remote_route' => 'place_typeahead',
            'class' => Place::class,
            'required' => false,
            'allow_clear' => true,
        ));
        $builder->add('deathPlace', Select2EntityType::class, array(
            'label' => 'Place',
            'multiple' => false,
            'remote_route' => 'place_typeahead',
            'class' => Place::class,
            'required' => false,
            'allow_clear' => true,
        ));
        
    }
    
    /**
     * Define options for the form.
     *
     * Set default, optional, and required options passed to the
     * buildForm() method via the $options parameter.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Person'
        ));
    }

}
