<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Archive;
use App\Entity\Coterie;
use App\Entity\Period;
use App\Entity\PrintSource;
use App\Entity\Region;
use App\Entity\Theme;
use App\Entity\Content;
use App\Entity\Person;
use App\Repository\ArchiveRepository;
use App\Repository\CoterieRepository;
use App\Repository\PeriodRepository;
use App\Repository\PrintSourceRepository;
use App\Repository\RegionRepository;
use App\Repository\ThemeRepository;
use App\Repository\ContentRepository;
use App\Repository\PersonRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderExecuterInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * ManuscriptFilterType form.
 */
class ManuscriptFilterType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('digitized', Filters\CheckboxFilterType::class, [
            'label' => 'Digitized manuscripts only',
            'help' => 'If this control is checked, only manuscripts that have been digitized will be included in search results.',
            'row_attr' => ['class' => 'filter filter_boolean filter_digitized'],
        ]);

        $builder->add('archive', EntityType::class, [
            'label' => 'Archives',
            'multiple' => true,
            'class' => Archive::class,
            'choice_value' => function (?Archive $archive): string {
                return $archive ? "archiveId:{$archive->getId()}" : '';
            },
            'query_builder' => function (ArchiveRepository $repo): QueryBuilder {
                return $repo->createQueryBuilder('u')->orderBy('u.label', 'ASC');
            },
            'required' => false,
            'placeholder' => 'Select one or more archives',
            'attr' => [
                'data-filter-type' => 'archiveId',
            ],
        ]);
        $builder->add('periods', EntityType::class, [
            'label' => 'Periods',
            'multiple' => true,
            'class' => Period::class,
            'choice_value' => function (?Period $period): string {
                return $period ? "periodId:{$period->getId()}" : '';
            },
            'query_builder' => function (PeriodRepository $repo): QueryBuilder {
                return $repo->createQueryBuilder('u')->orderBy('u.label', 'ASC');
            },
            'required' => false,
            'placeholder' => 'Select one or more periods',
            'attr' => [
                'data-filter-type' => 'periodId',
            ],
        ]);
        $builder->add('printSources', EntityType::class, [
            'label' => 'Print Sources',
            'multiple' => true,
            'class' => PrintSource::class,
            'choice_value' => function (?PrintSource $printSource): string {
                return $printSource ? "printSourceId:{$printSource->getId()}" : '';
            },
            'query_builder' => function (PrintSourceRepository $repo): QueryBuilder {
                return $repo->createQueryBuilder('u')->orderBy('u.label', 'ASC');
            },
            'required' => false,
            'placeholder' => 'Select one or more print sources',
            'attr' => [
                'data-filter-type' => 'printSourceId',
            ],
        ]);
        $builder->add('regions', EntityType::class, [
            'label' => 'Regions',
            'multiple' => true,
            'class' => Region::class,
            'choice_value' => function (?Region $region): string {
                return $region ? "regionId:{$region->getId()}" : '';
            },
            'query_builder' => function (RegionRepository $repo): QueryBuilder {
                return $repo->createQueryBuilder('u')->orderBy('u.name', 'ASC');
            },
            'required' => false,
            'placeholder' => 'Select one or more regions',
            'attr' => [
                'data-filter-type' => 'printSourceId',
            ],
        ]);
        $builder->add('majorThemes', EntityType::class, [
            'label' => 'Major Themes',
            'multiple' => true,
            'class' => Theme::class,
            'choice_value' => function (?Theme $theme): string {
                return $theme ? "majorThemeId:{$theme->getId()}" : '';
            },
            'query_builder' => function (ThemeRepository $repo): QueryBuilder {
                return $repo->createQueryBuilder('u')->orderBy('u.label', 'ASC');
            },
            'required' => false,
            'placeholder' => 'Select one or more major themes',
            'attr' => [
                'data-filter-type' => 'majorThemeId',
            ],
        ]);
        $builder->add('otherThemes', EntityType::class, [
            'label' => 'Minor Themes',
            'multiple' => true,
            'class' => Theme::class,
            'choice_value' => function (?Theme $theme): string {
                return $theme ? "otherThemeId:{$theme->getId()}" : '';
            },
            'query_builder' => function (ThemeRepository $repo): QueryBuilder {
                return $repo->createQueryBuilder('u')->orderBy('u.label', 'ASC');
            },
            'required' => false,
            'placeholder' => 'Select one or more minor themes',
            'attr' => [
                'data-filter-type' => 'otherThemeId',
            ],
        ]);
        $builder->add('coteries', EntityType::class, [
            'label' => 'Coteries',
            'multiple' => true,
            'class' => Coterie::class,
            'choice_value' => function (?Coterie $coterie): string {
                return $coterie ? "coterieId:{$coterie->getId()}" : '';
            },
            'query_builder' => function (CoterieRepository $repo): QueryBuilder {
                return $repo->createQueryBuilder('u')->orderBy('u.label', 'ASC');
            },
            'required' => false,
            'placeholder' => 'Select one or more coteries',
            'attr' => [
                'data-filter-type' => 'coterieId',
            ],
        ]);
        $builder->add('manuscriptContents', EntityType::class, [
            'label' => 'Poems in Manuscript',
            'multiple' => true,
            'class' => Content::class,
            'choice_value' => function (?Content $content): string {
                return $content ? "contentId:{$content->getId()}" : '';
            },
            'query_builder' => function (ContentRepository $repo): QueryBuilder {
                return $repo->createQueryBuilder('u')->orderBy('u.firstLine', 'ASC');
            },
            'required' => false,
            'placeholder' => 'Select one or more poems',
            'attr' => [
                'data-filter-type' => 'contentId',
            ],
        ]);
        $builder->add('manuscriptContributions', EntityType::class, [
            'label' => 'Manuscript Contributors',
            'multiple' => true,
            'class' => Person::class,
            'choice_value' => function (?Person $person): string {
                return $person ? "personId:{$person->getId()}" : '';
            },
            'query_builder' => function (PersonRepository $repo): QueryBuilder {
                return $repo->createQueryBuilder('u')->orderBy('u.sortableName', 'ASC');
            },
            'required' => false,
            'placeholder' => 'Select one or contributors',
            'attr' => [
                'data-filter-type' => 'personId',
            ],
        ]);
    }

    public function getBlockPrefix() {
        return 'ms_filter';
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'validation_groups' => ['filtering'],
        ]);
    }

    public function sortByLabel($repo) {
        return fn ($repo) => $repo->createQueryBuilder('u')
            ->orderBy('u.label', 'ASC')
        ;
    }

    public function sortByName($repo) {
        return fn ($repo) => $repo->createQueryBuilder('u')
            ->orderBy('u.name', 'ASC')
        ;
    }
}
