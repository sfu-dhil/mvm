<?php

namespace AppBundle\Form;

use AppBundle\Entity\Place;
use Nines\UtilBundle\Form\TermType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * PrintSourceType form.
 */
class PrintSourceType extends ArchiveSourceType
{
    /**
     * Add form fields to $builder.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('place', Select2EntityType::class, array(
            'label' => 'Place',
            'multiple' => false,
            'remote_route' => 'place_typeahead',
            'class' => Place::class,
            'required' => false,
            'allow_clear' => true,
            'attr' => array(
                'add_path' => 'place_new_popup',
                'add_label' => 'Add Place',
            )
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
            'data_class' => 'AppBundle\Entity\PrintSource'
        ));
    }

}
