<?php

declare(strict_types=1);

namespace App\Form;

use Nines\MediaBundle\Form\LinkType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class ManuscriptLinkType extends LinkType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        parent::buildForm($builder, $options);
        $builder->add('text', HiddenType::class, []);
    }
}
