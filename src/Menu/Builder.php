<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Menu builder for the navigation and search menus.
 */
class Builder implements ContainerAwareInterface {
    use ContainerAwareTrait;

    // U+25BE, black down-pointing small triangle.
    public const CARET = ' ▾';

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
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authChecker, TokenStorageInterface $tokenStorage) {
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
    private function hasRole($role) {
        if ( ! $this->tokenStorage->getToken()) {
            return false;
        }

        return $this->authChecker->isGranted($role);
    }

    /**
     * Build the navigation menu and return it.
     *
     * @return ItemInterface
     */
    public function mainMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav',
        ]);
        $menuItems = [
            'manuscripts' => 'Manuscripts',
            'people' => 'People, Coteries, Roles',
            'poems' => 'Poems'
        ];
        foreach ($menuItems as $name => $label) {
            $menuItems[$name] = $menu->addChild($name, [
                'uri' => '#',
                'label' => $label
            ]);
            $menuItems[$name]->setAttribute('dropdown', true);
            $menuItems[$name]->setAttribute('class','dropdown-toggle');
            $menuItems[$name]->setLinkAttribute('data-toggle', 'dropdown');
            $menuItems[$name]->setChildrenAttribute('class', 'dropdown-menu');
        }
        $menuItems['manuscripts']->addChild('Browse all MSS', [
            'route' => 'manuscript_index',
        ]);
        $menuItems['manuscripts']->addChild('Browse MSS by Archive', [
            'route' => 'archive_index',
        ]);
        $menuItems['manuscripts']->addChild('Browse MSS by Period', [
            'route' => 'period_index',
        ]);
        $menuItems['manuscripts']->addChild('Browse MSS by Feature', [
            'route' => 'feature_index',
        ]);
        $menuItems['manuscripts']->addChild('Browse MSS by Themes', [
            'route' => 'theme_index',
        ]);
        $menuItems['manuscripts']->addChild('Browse MSS by Print Sources', [
            'route' => 'print_source_index',
        ]);
        $menuItems['manuscripts']->addChild('Browse MSS by Region', [
            'route' => 'region_index'
        ]);
        if ($this->hasRole('ROLE_USER')) {
            $divider = $menuItems['manuscripts']->addChild('divider', [
                'label' => '',
            ]);
            $divider->setAttributes([
                'role' => 'separator',
                'class' => 'divider',
            ]);
            $menuItems['manuscripts']->addChild('Poem Contributions', [
                'route' => 'content_contribution_index',
            ]);
            $menuItems['manuscripts']->addChild('Manuscript Poems', [
                'route' => 'manuscript_content_index',
            ]);
            $menuItems['manuscripts']->addChild('Manuscript Contributions', [
                'route' => 'manuscript_contribution_index',
            ]);
            $menuItems['manuscripts']->addChild('Manuscript Features', [
                'route' => 'manuscript_feature_index',
            ]);
        }

        if ($this->hasRole('ROLE_ADMIN')) {
            $divider2 = $menuItems['manuscripts']->addChild('divider2', [
                'label' => '',
            ]);
            $divider2->setAttributes([
                'role' => 'separator',
                'class' => 'divider',
            ]);
            $menuItems['manuscripts']->addChild('Links', [
                'route' => 'nines_media_link_index',
            ]);
        }
        $menuItems['people']->addChild('Browse all People', [
            'route' => 'person_index',
        ]);
        $menuItems['people']->addChild('Browse all Coteries', [
            'route' => 'coterie_index',
        ]);
        $menuItems['people']->addChild('Browse Poem Roles', [
            'route' => 'content_role_index',
        ]);
        $menuItems['people']->addChild('Manuscript Roles', [
            'route' => 'manuscript_role_index',
        ]);
        $menuItems['poems']->addChild('Browse all Poems', [
            'route' => 'content_index',
        ]);

        return $menu;
    }

    /**
     * Build a menu the footer.
     *
     * @return ItemInterface
     */
    public function footerMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'footer-links',
        ]);
        $menu->addChild('Home', [
            'route' => 'homepage',
        ]);
        $menu->addChild('Archives', [
            'route' => 'archive_index',
        ]);
        $menu->addChild('Poems', [
            'route' => 'content_index',
        ]);
        $menu->addChild('Poem Roles', [
            'route' => 'content_role_index',
        ]);
        $menu->addChild('Features', [
            'route' => 'feature_index',
        ]);
        $menu->addChild('Manuscripts', [
            'route' => 'manuscript_index',
        ]);
        $menu->addChild('Manuscript Roles', [
            'route' => 'manuscript_role_index',
        ]);
        $menu->addChild('Periods', [
            'route' => 'period_index',
        ]);
        $menu->addChild('People', [
            'route' => 'person_index',
        ]);
        $menu->addChild('Print Sources', [
            'route' => 'print_source_index',
        ]);
        $menu->addChild('Regions', [
            'route' => 'region_index',
        ]);
        $menu->addChild('Themes', [
            'route' => 'theme_index',
        ]);
        $menu->addChild('Privacy', [
            'route' => 'privacy',
        ]);

        return $menu;
    }
}
