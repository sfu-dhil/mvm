<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\Archive;
use App\Entity\Coterie;
use App\Entity\Content;
use App\Entity\Period;

use App\Entity\Region;
use App\Entity\Theme;
use App\Entity\PrintSource;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;
use PHPUnit\Util\Filter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderExecuterInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;

/**
 * ManuscriptFilterType form.
 */
class ManuscriptFilterType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('search', Filters\TextFilterType::class, [
            'apply_filter' => function(QueryInterface $filterQuery, $field, $values){
            if (empty($values['value'])) {
                return null;
            }

            $expr = $filterQuery->getExpr();
            return $expr;
        }
        ]);

        $builder->add('digitized', Filters\BooleanFilterType::class, [
            'placeholder' => '',
            'expanded' => true,
            'row_attr' => ['class' => 'filter filter_boolean filter_digitized']
        ]);
        $builder->add('untitled', Filters\BooleanFilterType::class, [
            'placeholder' => '',
            'expanded' => true,
            'row_attr' => ['class' => 'filter filter_boolean filter_untitled']
        ]);
        $builder->add('archive', Filters\EntityFilterType::class, [
            'class' => Archive::class,
            'multiple' => true,
            'row_attr' => ['class' => 'filter filter_entity filter_archive'],
            'query_builder' => $this->sortByLabel(ArchiveRepository::class)
        ]);
        $builder->add('periods', Filters\EntityFilterType::class, [
            'class' => Period::class,
            'multiple' => true,
            'row_attr' => ['class' => 'filter filter_entity filter_periods'],
            'query_builder' => $this->sortByLabel(PeriodRepository::class)
        ]);
        $builder->add('printSources', Filters\EntityFilterType::class,[
            'class' => PrintSource::class,
            'multiple' => true,
            'row_attr' => ['class' => 'filter filter_entity filter_printSources'],
            'query_builder' => $this->sortByLabel(PrintSourceRepository::class)
        ]);

        $builder->add('majorThemes', Filters\EntityFilterType::class, [
            'class' => Theme::class,
            'multiple' => true,
            'row_attr' => ['class' => 'filter filter_entity filter_majorThemes'],
            'query_builder' => $this->sortByLabel(ThemeRepository::class)
        ]);
        $builder->add('otherThemes', Filters\EntityFilterType::class, [
            'class' => Theme::class,
            'multiple' => true,
            'label' => 'Minor Themes',
            'row_attr' => ['class' => 'filter filter_entity filter_minorThemes'],
            'query_builder' => $this->sortByLabel(ThemeRepository::class)
        ]);
        $builder->add('regions',Filters\EntityFilterType::class, [
            'class' => Region::class,
            'multiple' => true,
            'row_attr' => ['class' => 'filter filter_entity filter_region'],
            'query_builder' => $this->sortByName(RegionRepository::class)
        ]);

        $builder->add('coteries', Filters\EntityFilterType::class, [
            'class' => Coterie::class,
            'multiple' => true,
            'row_attr' => ['class' => 'filter filter_entity filter_coterie'],
            'query_builder' => $this->sortByLabel(CoterieRepository::class)
        ]);


        $builder->add('manuscriptContents', Filters\CollectionAdapterFilterType::class, [
            'entry_type' => ManuscriptContentFilterType::class,
            'label' => false,
            'add_shared' => function(FilterBuilderExecuterInterface $qbe){
                $closure = function(QueryBuilder $filterBuilder, $alias, $joinAlias, Expr $expr){
                    $filterBuilder->leftJoin($alias . '.manuscriptContents', $joinAlias);
                };
                $qbe->addOnce($qbe->getAlias().'.manuscriptContents', 'contents', $closure);
            },
            'row_attr' => ['class' => 'filter filter_entity filter_manuscriptContents'],
        ]);

        $builder->add('manuscriptContributions', Filters\CollectionAdapterFilterType::class, [
            'entry_type' => ManuscriptContributionFilterType::class,
            'label' => false,
            'add_shared' => function(FilterBuilderExecuterInterface $qbe){
                $closure = function(QueryBuilder $filterBuilder, $alias, $joinAlias, Expr $expr){
                    $filterBuilder->leftJoin($alias . '.manuscriptContributions', $joinAlias);
                };
                $qbe->addOnce($qbe->getAlias().'.manuscriptContributions', 'contributions', $closure);
            },
            'row_attr' => ['class' => 'filter filter_entity filter_manuscriptContributions'],
        ]);

    }

    public function getBlockPrefix(){
        return 'ms_filter';
    }

    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults([
            'csrf_protection' => false,
            'validation_groups' => ['filtering'],
        ]);
    }

    public function sortByLabel($repo){
        return function($repo){
            return $repo->createQueryBuilder('u')
                ->orderBy('u.label', 'ASC');
        };
    }

    public function sortByName($repo){
        return function($repo) {
            return $repo->createQueryBuilder('u')
                ->orderBy('u.name', 'ASC');
        };
    }


};