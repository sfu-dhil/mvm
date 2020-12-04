<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\Archive;
use App\Entity\Period;
use App\Entity\PrintSource;
use App\Entity\Region;
use App\Entity\Theme;
use Nines\MediaBundle\Entity\LinkableInterface;
use Nines\MediaBundle\Form\LinkType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * ManuscriptType form.
 */
class ManuscriptType extends AbstractType {
    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $manuscript = $options['entity'];
        $builder->add('untitled', CheckboxType::class, [
            'label' => 'Untitled',
            'required' => false,
            'attr' => [
                'help_block' => 'Is the manuscript untitled?',
            ],
        ]);
        $builder->add('title', null, [
            'label' => 'Title',
            'required' => true,
            'attr' => [
                'help_block' => 'Enter the title of the manuscript or a descriptive identifier for an untitled manuscript. Do not include square brackets.',
            ],
        ]);
        $builder->add('callNumber', null, [
            'label' => 'Call Number',
            'required' => true,
            'attr' => ['help_block' => 'May also be called shelf mark. Do not include the name of the institution here, unless it is part of the call number.'],
        ]);
        $builder->add('archive', Select2EntityType::class, [
            'label' => 'Archive',
            'multiple' => false,
            'remote_route' => 'archive_typeahead',
            'class' => Archive::class,
            'required' => false,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'archive_new_popup',
                'add_label' => 'Add Archive Source',
            ],
        ]);
        $builder->add('periods', Select2EntityType::class, [
            'label' => 'Periods',
            'multiple' => true,
            'required' => false,
            'remote_route' => 'period_typeahead',
            'class' => Period::class,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'period_new_popup',
                'add_label' => 'Add Period',
            ],
        ]);
        $builder->add('format', TextType::class, [
            'label' => 'Format',
            'required' => false,
            'attr' => ['help_block' => 'What is the paper format of the manuscript? Quarto, folio, etc.'],
        ]);
        $builder->add('size', TextType::class, [
            'label' => 'Book Size',
            'required' => false,
            'attr' => ['help_block' => 'What is the book size? Use height x width and include units. Eg. 18cm x 10cm'],
        ]);
        $builder->add('firstLineIndex', CheckboxType::class, [
            'label' => 'First Line Index',
            'required' => false,
            'attr' => ['help_block' => 'Does the archive catalog include a first line index?'],
        ]);
        $builder->add('digitized', CheckboxType::class, [
            'label' => 'Digitized',
            'required' => false,
            'attr' => ['help_block' => 'Has the archive digitized the manuscript?'],
        ]);
        $builder->add('filledPageCount', null, [
            'label' => 'Filled Count',
            'required' => false,
            'attr' => ['help_block' => 'Use filled page or leaf count and include the unit.'],
        ]);
        $builder->add('itemCount', null, [
            'label' => 'Item Count',
            'required' => false,
            'attr' => ['help_block' => ''],
        ]);
        $builder->add('poemCount', null, [
            'label' => 'Poem Count',
            'required' => false,
            'attr' => ['help_block' => ''],
        ]);
        $builder->add('regions', Select2EntityType::class, [
            'label' => 'Region',
            'multiple' => true,
            'remote_route' => 'region_typeahead',
            'class' => Region::class,
            'required' => false,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'region_new_popup',
                'add_label' => 'Add Region',
            ],
        ]);
        $builder->add('additionalGenres', CollectionType::class, [
            'label' => 'Additional Genres',
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'entry_type' => TextType::class,
            'entry_options' => ['label' => false],
            'required' => false,
            'attr' => [
                'help_block' => '',
                'class' => 'collection collection-simple',
            ],
        ]);
        $builder->add('printSources', Select2EntityType::class, [
            'label' => 'Print Sources',
            'multiple' => true,
            'remote_route' => 'print_source_typeahead',
            'class' => PrintSource::class,
            'required' => false,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'print_source_new_popup',
                'add_label' => 'Add Print Source',
                'help_block' => 'Any print sources of the content listed in the manuscript, if not included with selected content entries.',
            ],
        ]);
        $builder->add('themes', Select2EntityType::class, [
            'label' => 'Themes',
            'multiple' => true,
            'remote_route' => 'theme_typeahead',
            'class' => Theme::class,
            'required' => false,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'theme_new_popup',
                'add_label' => 'Add Theme',
            ],
        ]);
        $builder->add('description', null, [
            'label' => 'Description',
            'required' => false,
            'attr' => [
                'help_block' => '',
                'class' => 'tinymce',
            ],
        ]);
        $builder->add('links', CollectionType::class, [
            'label' => 'Links',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => LinkType::class,
            'entry_options' => [
                'label' => false,
            ],
            'by_reference' => false,
            'attr' => [
                'class' => 'collection collection-complex',
                'help_block' => '',
            ],
            'mapped' => false,
            'data' => $manuscript->getLinks(),
        ]);
        $builder->add('bibliography', TextType::class, [
            'label' => 'Bibliography',
            'required' => false,
            'attr' => [
                'help_block' => 'Formatted bibliography of works which reference this manuscript',
                'class' => 'tinymce',
            ],
        ]);
    }

    /**
     * Define options for the form.
     *
     * Set default, optional, and required options passed to the
     * buildForm() method via the $options parameter.
     */
    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults(['data_class' => 'App\Entity\Manuscript']);
        $resolver->setRequired([
            LinkableInterface::class => 'entity',
        ]);
    }
}
