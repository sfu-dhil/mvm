<?php

namespace AppBundle\Form;

use AppBundle\Entity\Archive;
use AppBundle\Entity\Period;
use AppBundle\Entity\Region;
use AppBundle\Entity\PrintSource;
use AppBundle\Entity\Theme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * ManuscriptType form.
 */
class ManuscriptType extends AbstractType {

    /**
     * Add form fields to $builder.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('untitled', CheckboxType::class, array(
            'label'    => 'Untitled',
            'required' => false,
            'attr'     => array(
                'help_block' => 'Is the manuscript untitled?',
            ),
        ));
        $builder->add('title', null, array(
            'label'    => 'Title',
            'required' => true,
            'attr'     => array(
                'help_block' => 'Enter the title of the manuscript or a descriptive identifier for an untitled manuscript. Do not include square brackets.',
            ),
        ));
        $builder->add('callNumber', null, array(
            'label'    => 'Call Number',
            'required' => true,
            'attr'     => array('help_block' => 'May also be called shelf mark. Do not include the name of the institution here, unless it is part of the call number.'),
        ));
        $builder->add('archive', Select2EntityType::class, array(
            'label'        => 'Archive',
            'multiple'     => false,
            'remote_route' => 'archive_typeahead',
            'class'        => Archive::class,
            'required'     => false,
            'allow_clear'  => true,
            'attr'         => array(
                'add_path'  => 'archive_new_popup',
                'add_label' => 'Add Archive Source',
            ),
        ));
        $builder->add('period', Select2EntityType::class, array(
            'label'        => 'Period',
            'multiple'     => false,
            'required'     => false,
            'remote_route' => 'period_typeahead',
            'class'        => Period::class,
            'allow_clear'  => true,
            'attr'         => array(
                'add_path'  => 'period_new_popup',
                'add_label' => 'Add Period',
            ),
        ));
        $builder->add('format', TextType::class, array(
            'label'    => 'Format',
            'required' => false,
            'attr'     => array('help_block' => 'What is the paper format of the manuscript? Quarto, folio, etc.'),
        ));
        $builder->add('size', TextType::class, array(
            'label'    => 'Book Size',
            'required' => false,
            'attr'     => array('help_block' => 'What is the book size? Use height x width and include units. Eg. 18cm x 10cm'),
        ));
        $builder->add('firstLineIndex', CheckboxType::class, array(
            'label'    => 'First Line Index',
            'required' => false,
            'attr'     => array('help_block' => 'Does the archive catalog include a first line index?',),
        ));
        $builder->add('digitized', CheckboxType::class, array(
            'label'    => 'Digitized',
            'required' => false,
            'attr'     => array('help_block' => 'Has the archive digitized the manuscript?'),
        ));
        $builder->add('filledPageCount', null, array(
            'label'    => 'Filled Page Count',
            'required' => false,
            'attr'     => array('help_block' => '',),
        ));
        $builder->add('itemCount', null, array(
            'label'    => 'Item Count',
            'required' => false,
            'attr'     => array('help_block' => '',),
        ));
        $builder->add('poemCount', null, array(
            'label'    => 'Poem Count',
            'required' => false,
            'attr'     => array('help_block' => '',),
        ));
        $builder->add('region', Select2EntityType::class, array(
            'label'        => 'Region',
            'multiple'     => false,
            'remote_route' => 'region_typeahead',
            'class'        => Region::class,
            'required'     => false,
            'allow_clear'  => true,
            'attr'         => array(
                'add_path'  => 'region_new_popup',
                'add_label' => 'Add Region',
            ),
        ));
        $builder->add('additionalGenres', CollectionType::class, array(
            'label'         => 'Additional Genres',
            'allow_add'     => true,
            'allow_delete'  => true,
            'prototype'     => true,
            'entry_type'    => TextType::class,
            'entry_options' => array('label' => false,),
            'required'      => false,
            'attr'          => array(
                'help_block' => '',
                'class'      => 'collection collection-simple',
            ),
        ));
        $builder->add('printSources', Select2EntityType::class, array(
            'label'        => 'Print Sources',
            'multiple'     => true,
            'remote_route' => 'print_source_typeahead',
            'class'        => PrintSource::class,
            'required'     => false,
            'allow_clear'  => true,
            'attr'         => array(
                'add_path'   => 'print_source_new_popup',
                'add_label'  => 'Add Print Source',
                'help_block' => 'Any print sources of the content listed in the manuscript, if not included with selected content entries.',
            ),
        ));
        $builder->add('themes', Select2EntityType::class, array(
            'label'        => 'Themes',
            'multiple'     => true,
            'remote_route' => 'theme_typeahead',
            'class'        => Theme::class,
            'required'     => false,
            'allow_clear'  => true,
            'attr'         => array(
                'add_path'  => 'theme_new_popup',
                'add_label' => 'Add Theme',
            ),
        ));
        $builder->add('description', null, array(
            'label'    => 'Description',
            'required' => false,
            'attr'     => array(
                'help_block' => '',
                'class'      => 'tinymce',
            ),
        ));
        $builder->add('bibliography', TextType::class, array(
            'label'    => 'Bibliography',
            'required' => false,
            'attr'     => array(
                'help_block' => 'Formatted bibliography of works which reference this manuscript',
                'class'      => 'tinymce',
            ),
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
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array('data_class' => 'AppBundle\Entity\Manuscript'));
    }

}
