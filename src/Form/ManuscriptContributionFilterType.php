<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderExecuterInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EmbeddedFilterTypeInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;


/**
 * ManuscriptFilterType form.
 */
class ManuscriptContributionFilterType extends AbstractType implements EmbeddedFilterTypeInterface{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('person', Filters\EntityFilterType::class, [
            'class' => Person::class,
            'multiple' => true,
            'query_builder' => function(PersonRepository $repo){
                  return $repo->createQueryBuilder('u')
                        ->orderBy('u.sortableName', 'ASC');
                },
        ]);
    }

    public function getParent()
    {
        return Filters\SharedableFilterType::class; // this allow us to use the "add_shared" option
    }

    public function getBlockPrefix(){
        return 'contribution_filter';
    }

};





