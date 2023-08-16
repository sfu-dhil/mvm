<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Archive;
use App\Entity\Manuscript;
use App\Entity\Period;
use App\Entity\PrintSource;
use App\Entity\Region;
use App\Entity\Theme;
use Nines\MediaBundle\Form\Mapper\LinkableMapper;
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
    public function __construct(
        public LinkableMapper $mapper,
    ) {
    }

    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('untitled', CheckboxType::class, [
            'label' => 'Untitled',
            'required' => false,
            'help' => 'Is the manuscript untitled?',
        ]);
        $builder->add('title', null, [
            'label' => 'Title',
            'required' => true,
            'help' => 'Enter the title of the manuscript or a descriptive identifier for an untitled manuscript. Do not include square brackets.',
        ]);
        $builder->add('callNumber', null, [
            'label' => 'Call Number',
            'required' => true,
            'help' => 'May also be called shelf mark. Do not include the name of the institution here, unless it is part of the call number.',
        ]);
        $builder->add('archive', Select2EntityType::class, [
            'label' => 'Archive',
            'multiple' => false,
            'remote_route' => 'archive_typeahead',
            'class' => Archive::class,
            'required' => false,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'archive_new',
                'add_label' => 'Add Archive Source',
            ],
            'placeholder' => 'Search for an existing archive source by name',
        ]);
        $builder->add('periods', Select2EntityType::class, [
            'label' => 'Periods',
            'multiple' => true,
            'required' => false,
            'remote_route' => 'period_typeahead',
            'class' => Period::class,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'period_new',
                'add_label' => 'Add Period',
            ],
            'placeholder' => 'Search for an existing period by name',
        ]);
        $builder->add('format', TextType::class, [
            'label' => 'Format',
            'required' => false,
            'help' => 'What is the paper format of the manuscript? Quarto, folio, etc.',
        ]);
        $builder->add('size', TextType::class, [
            'label' => 'Book Size',
            'required' => false,
            'help' => 'What is the book size? Use height x width and include units. Eg. 18cm x 10cm',
        ]);
        $builder->add('firstLineIndex', CheckboxType::class, [
            'label' => 'First Line Index',
            'required' => false,
            'help' => 'Does the archive catalog include a first line index?',
        ]);
        $builder->add('digitized', CheckboxType::class, [
            'label' => 'Digitized',
            'required' => false,
            'help' => 'Has the archive digitized the manuscript?',
        ]);
        $builder->add('filledPageCount', null, [
            'label' => 'Filled Count',
            'required' => false,
            'help' => 'Use filled page or leaf count and include the unit.',
        ]);
        $builder->add('itemCount', null, [
            'label' => 'Item Count',
            'required' => false,
        ]);
        $builder->add('poemCount', null, [
            'label' => 'Poem Count',
            'required' => false,
        ]);
        $builder->add('regions', Select2EntityType::class, [
            'label' => 'Region',
            'multiple' => true,
            'remote_route' => 'region_typeahead',
            'class' => Region::class,
            'required' => false,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'region_new',
                'add_label' => 'Add Region',
            ],
            'placeholder' => 'Search for an existing region by name',
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
            'help' => 'Any print sources of the content listed in the manuscript, if not included with selected content entries.',
            'attr' => [
                'add_path' => 'print_source_new',
                'add_label' => 'Add Print Source',
            ],
            'placeholder' => 'Search for an existing print source by name, description or region name',
        ]);
        $builder->add('majorThemes', Select2EntityType::class, [
            'label' => 'Major Themes',
            'multiple' => true,
            'remote_route' => 'theme_typeahead',
            'class' => Theme::class,
            'required' => false,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'theme_new',
                'add_label' => 'Add Theme',
            ],
            'placeholder' => 'Search for an existing theme by name',
        ]);
        $builder->add('otherThemes', Select2EntityType::class, [
            'label' => 'Minor Themes',
            'multiple' => true,
            'remote_route' => 'theme_typeahead',
            'class' => Theme::class,
            'required' => false,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'theme_new',
                'add_label' => 'Add Theme',
            ],
            'placeholder' => 'Search for an existing theme by name',
        ]);
        $builder->add('description', null, [
            'label' => 'Description',
            'required' => false,
            'attr' => [
                'class' => 'tinymce',
            ],
        ]);
        $builder->add('bibliography', TextType::class, [
            'label' => 'Bibliography',
            'required' => false,
            'help' => 'Formatted bibliography of works which reference this manuscript',
            'attr' => [
                'class' => 'tinymce',
            ],
        ]);
        $builder->add('citation', TextType::class, [
            'label' => 'Citation',
            'required' => false,
            'help' => 'Recommended citation for this manuscript, without the "Accessed on" date',
            'attr' => [
                'class' => 'tinymce',
            ],
        ]);
        $builder->add('links', CollectionType::class, [
            'label' => 'Links',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => ManuscriptLinkType::class,
            'entry_options' => [
                'label' => false,
            ],
            'attr' => [
                'class' => 'collection collection-simple',
            ],
            'mapped' => false,
        ]);
        $builder->setDataMapper($this->mapper);
    }

    /**
     * Define options for the form.
     *
     * Set default, optional, and required options passed to the
     * buildForm() method via the $options parameter.
     */
    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Manuscript::class,
        ]);
    }
}
