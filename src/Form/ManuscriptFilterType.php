<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Archive;
use App\Entity\Coterie;
use App\Entity\Period;
use App\Entity\PrintSource;
use App\Entity\Region;
use App\Entity\Theme;
use App\Repository\ArchiveRepository;
use App\Repository\CoterieRepository;
use App\Repository\PeriodRepository;
use App\Repository\RegionRepository;
use App\Repository\ThemeRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderExecuterInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

        $builder->add('archive', Filters\EntityFilterType::class, [
            'class' => Archive::class,
            'label' => 'Archives',
            'multiple' => true,
            'row_attr' => ['class' => 'filter filter_entity filter_archive'],
            'query_builder' => $this->sortByLabel(ArchiveRepository::class),
        ]);
        $builder->add('periods', Filters\EntityFilterType::class, [
            'class' => Period::class,
            'multiple' => true,
            'row_attr' => ['class' => 'filter filter_entity filter_periods'],
            'query_builder' => $this->sortByLabel(PeriodRepository::class),
        ]);
        $builder->add('printSources', Filters\EntityFilterType::class, [
            'class' => PrintSource::class,
            'multiple' => true,
            'label' => 'Print Sources',
            'row_attr' => ['class' => 'filter filter_entity filter_printSources'],
            'query_builder' => $this->sortByLabel(PrintSource::class),
        ]);
        $builder->add('regions', Filters\EntityFilterType::class, [
            'class' => Region::class,
            'multiple' => true,
            'row_attr' => ['class' => 'filter filter_entity filter_region'],
            'query_builder' => $this->sortByName(RegionRepository::class),
        ]);

        $builder->add('majorThemes', Filters\EntityFilterType::class, [
            'class' => Theme::class,
            'multiple' => true,
            'label' => 'Major Themes',
            'row_attr' => ['class' => 'filter filter_entity filter_majorThemes'],
            'query_builder' => $this->sortByLabel(ThemeRepository::class),
        ]);
        $builder->add('otherThemes', Filters\EntityFilterType::class, [
            'class' => Theme::class,
            'multiple' => true,
            'label' => 'Minor Themes',
            'row_attr' => ['class' => 'filter filter_entity filter_minorThemes'],
            'query_builder' => $this->sortByLabel(ThemeRepository::class),
        ]);

        $builder->add('coteries', Filters\EntityFilterType::class, [
            'class' => Coterie::class,
            'multiple' => true,
            'row_attr' => ['class' => 'filter filter_entity filter_coterie'],
            'query_builder' => $this->sortByLabel(CoterieRepository::class),
        ]);

        $builder->add('manuscriptContents', Filters\CollectionAdapterFilterType::class, [
            'entry_type' => ManuscriptContentFilterType::class,
            'label' => false,
            'add_shared' => function (FilterBuilderExecuterInterface $qbe) : void {
                $closure = function (QueryBuilder $filterBuilder, $alias, $joinAlias, Expr $expr) : void {
                    $filterBuilder->leftJoin($alias . '.manuscriptContents', $joinAlias);
                };
                $qbe->addOnce($qbe->getAlias() . '.manuscriptContents', 'contents', $closure);
            },
            'entry_options' => [
                'label' => false,
            ],
            'row_attr' => ['class' => 'filter filter_entity filter_collection filter_manuscriptContents'],
        ]);

        $builder->add('manuscriptContributions', Filters\CollectionAdapterFilterType::class, [
            'entry_type' => ManuscriptContributionFilterType::class,
            'label' => false,
            'add_shared' => function (FilterBuilderExecuterInterface $qbe) : void {
                $closure = function (QueryBuilder $filterBuilder, $alias, $joinAlias, Expr $expr) : void {
                    $filterBuilder->leftJoin($alias . '.manuscriptContributions', $joinAlias);
                };
                $qbe->addOnce($qbe->getAlias() . '.manuscriptContributions', 'contributions', $closure);
            },
            'entry_options' => [
                'label' => false,
            ],
            'row_attr' => ['class' => 'filter filter_entity filter_collection filter_manuscriptContributions'],
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
