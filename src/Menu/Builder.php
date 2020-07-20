<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
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

        $browse = $menu->addChild('browse', [
            'uri' => '#',
            'label' => 'Explore ' . self::CARET,
        ]);
        $browse->setAttribute('dropdown', true);
        $browse->setLinkAttribute('class', 'dropdown-toggle');
        $browse->setLinkAttribute('data-toggle', 'dropdown');
        $browse->setChildrenAttribute('class', 'dropdown-menu');

        $browse->addChild('Archives', [
            'route' => 'archive_index',
        ]);
        $browse->addChild('Contents', [
            'route' => 'content_index',
        ]);
        $browse->addChild('Content Roles', [
            'route' => 'content_role_index',
        ]);
        $browse->addChild('Features', [
            'route' => 'feature_index',
        ]);
        $browse->addChild('Manuscripts', [
            'route' => 'manuscript_index',
        ]);
        $browse->addChild('Manuscript Roles', [
            'route' => 'manuscript_role_index',
        ]);
        $browse->addChild('Periods', [
            'route' => 'period_index',
        ]);
        $browse->addChild('People', [
            'route' => 'person_index',
        ]);
        $browse->addChild('Print Sources', [
            'route' => 'print_source_index',
        ]);
        $browse->addChild('Regions', [
            'route' => 'region_index',
        ]);
        $browse->addChild('Themes', [
            'route' => 'theme_index',
        ]);

        if ($this->hasRole('ROLE_USER')) {
            $divider = $browse->addChild('divider', [
                'label' => '',
            ]);
            $divider->setAttributes([
                'role' => 'separator',
                'class' => 'divider',
            ]);
            $browse->addChild('Content Contributions', [
                'route' => 'content_contribution_index',
            ]);
            $browse->addChild('Manuscript Contents', [
                'route' => 'manuscript_content_index',
            ]);
            $browse->addChild('Manuscript Contributions', [
                'route' => 'manuscript_contribution_index',
            ]);
            $browse->addChild('Manuscript Features', [
                'route' => 'manuscript_feature_index',
            ]);
        }

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
        $menu->addChild('Contents', [
            'route' => 'content_index',
        ]);
        $menu->addChild('Content Roles', [
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
        $menu->addChild('Documentation', [
            'uri' => 'docs/sphinx/index.html',
        ]);


        return $menu;
    }
}
