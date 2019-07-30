<?php

namespace AppBundle\Form;

use AppBundle\Entity\Feature;
use AppBundle\Entity\Manuscript;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * ManuscriptFeatureType form.
 */
class ManuscriptFeatureType extends AbstractType
{
    /**
     * Add form fields to $builder.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        $builder->add('note', null, array(
            'label' => 'Note',
            'required' => true,
            'attr' => array(
                'help_block' => '',
                'class' => 'tinymce',
            ),
        ));
        $builder->add('feature', Select2EntityType::class, array(
            'label' => 'Manuscript',
            'multiple' => false,
            'remote_route' => 'feature_typeahead',
            'class' => Feature::class,
            'required' => true,
            'allow_clear' => true,
        ));
        $builder->add('manuscript', Select2EntityType::class, array(
            'label' => 'Manuscript',
            'multiple' => false,
            'remote_route' => 'manuscript_typeahead',
            'class' => Manuscript::class,
            'required' => true,
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
            'data_class' => 'AppBundle\Entity\ManuscriptFeature'
        ));
    }

}
