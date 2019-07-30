<?php

namespace AppBundle\Form;

use AppBundle\Entity\ArchiveSource;
use AppBundle\Entity\Period;
use AppBundle\Entity\Place;
use AppBundle\Entity\PrintSource;
use AppBundle\Entity\Theme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * ManuscriptType form.
 */
class ManuscriptType extends AbstractType
{
    /**
     * Add form fields to $builder.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        $builder->add('title', null, array(
            'label' => 'Title',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                $builder->add('description', null, array(
            'label' => 'Description',
            'required' => true,
            'attr' => array(
                'help_block' => '',
                'class' => 'tinymce',
            ),
        ));
                $builder->add('blankPageCount', null, array(
            'label' => 'Blank Page Count',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                $builder->add('filledPageCount', null, array(
            'label' => 'Filled Page Count',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                $builder->add('itemCount', null, array(
            'label' => 'Item Count',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                $builder->add('poemCount', null, array(
            'label' => 'Poem Count',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('additionalGenres', CollectionType::class, array(
            'label' => 'Additional Genres',
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'entry_type' => TextType::class,
            'entry_options' => array(
                'label' => false,
            ),
            'required' => false,
            'attr' => array(
                'help_block' => '',
                'class' => 'collection collection-simple',
            ),
        ));
        $builder->add('place', Select2EntityType::class, array(
            'label' => 'Place',
            'multiple' => false,
            'remote_route' => 'place_typeahead',
            'class' => Place::class,
            'required' => true,
            'allow_clear' => true,
        ));
        $builder->add('period', Select2EntityType::class, array(
            'label' => 'Period',
            'multiple' => false,
            'remote_route' => 'period_typeahead',
            'class' => Period::class,
            'required' => true,
            'allow_clear' => true,
        ));
        $builder->add('archiveSource', Select2EntityType::class, array(
            'label' => 'Archive Source',
            'multiple' => false,
            'remote_route' => 'archive_source_typeahead',
            'class' => ArchiveSource::class,
            'required' => true,
            'allow_clear' => true,
        ));
        $builder->add('printSources', Select2EntityType::class, array(
            'label' => 'Print Sources',
            'multiple' => true,
            'remote_route' => 'print_source_typeahead',
            'class' => PrintSource::class,
            'required' => false,
            'allow_clear' => true,
        ));
        $builder->add('themes', Select2EntityType::class, array(
            'label' => 'Themes',
            'multiple' => true,
            'remote_route' => 'theme_typeahead',
            'class' => Theme::class,
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
            'data_class' => 'AppBundle\Entity\Manuscript'
        ));
    }

}
