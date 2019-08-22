<?php

namespace AppBundle\Menu;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Nines\BlogBundle\Entity\Post;
use Nines\BlogBundle\Entity\PostCategory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Menu builder for the navigation and search menus.
 */
class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    // U+25BE, black down-pointing small triangle.
    const CARET = ' ▾';

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * Build the menu builder.
     *
     * @param FactoryInterface $factory
     * @param AuthorizationCheckerInterface $authChecker
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authChecker, TokenStorageInterface $tokenStorage)
    {
        $this->factory = $factory;
        $this->authChecker = $authChecker;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Check if the current user is both logged in and granted a role.
     *
     * @param string $role
     *
     * @return bool
     */
    private function hasRole($role)
    {
        if (!$this->tokenStorage->getToken()) {
            return false;
        }
        return $this->authChecker->isGranted($role);
    }

    /**
     * Build the navigation menu and return it.
     *
     * @param array $options
     * @return ItemInterface
     */
    public function mainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(array(
            'class' => 'nav navbar-nav',
        ));

        $browse = $menu->addChild('browse', array(
            'uri' => '#',
            'label' => 'Explore ' . self::CARET,
        ));
        $browse->setAttribute('dropdown', true);
        $browse->setLinkAttribute('class', 'dropdown-toggle');
        $browse->setLinkAttribute('data-toggle', 'dropdown');
        $browse->setChildrenAttribute('class', 'dropdown-menu');

        $browse->addChild("Archive Sources", array(
            'route' => 'archive_index',
        ));
        $browse->addChild("Content Contributions", array(
            'route' => 'content_contribution_index',
        ));
        $browse->addChild("Contents", array(
            'route' => 'content_index',
        ));
        $browse->addChild("Content Roles", array(
            'route' => 'content_role_index',
        ));
        $browse->addChild("Features", array(
            'route' => 'feature_index',
        ));
        $browse->addChild("Images", array(
            'uri' => '#',
        ));
        $browse->addChild("Manuscripts", array(
            'route' => 'manuscript_index',
        ));
        $browse->addChild("Manuscript Roles", array(
            'route' => 'manuscript_role_index',
        ));
        $browse->addChild("Periods", array(
            'route' => 'period_index',
        ));
        $browse->addChild("People", array(
            'route' => 'person_index',
        ));
        $browse->addChild("Regions", array(
            'route' => 'region_index',
        ));
        $browse->addChild("Print Sources", array(
            'route' => 'print_source_index',
        ));
        $browse->addChild("Themes", array(
            'route' => 'theme_index',
        ));

        if ($this->hasRole('ROLE_USER')) {
            $divider = $browse->addChild('divider', array(
                'label' => '',
            ));
            $divider->setAttributes(array(
                'role' => 'separator',
                'class' => 'divider',
            ));
            $browse->addChild("Manuscript Contributions", array(
                'route' => 'manuscript_contribution_index',
            ));
            $browse->addChild("Manuscript Features", array(
                'route' => 'manuscript_feature_index',
            ));
        }

        return $menu;
    }

}
