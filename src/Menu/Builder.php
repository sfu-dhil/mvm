<?php

declare(strict_types=1);

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
    final public const CARET = ' ▾';

    public function __construct(
        private FactoryInterface $factory,
        private AuthorizationCheckerInterface $authChecker,
        private TokenStorageInterface $tokenStorage
    ) {
    }

    private function hasRole(string $role) : bool {
        if ( ! $this->tokenStorage->getToken()) {
            return false;
        }

        return $this->authChecker->isGranted($role);
    }

    public function mainMenu(array $options) : ItemInterface {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav',
        ]);
        $menuItems = [
            'manuscripts' => 'Manuscripts',
            'people' => 'People, Coteries, Roles',
            'poems' => 'Poems',
        ];
        foreach ($menuItems as $name => $label) {
            $menuItems[$name] = $menu->addChild($name, [
                'uri' => '#',
                'label' => $label . " " . $this::CARET,
                'attributes' => [
                    'class' => 'nav-item dropdown',
                ],
                'linkAttributes' => [
                    'class' => 'nav-link dropdown-toggle',
                    'role' => 'button',
                    'data-bs-toggle' => 'dropdown',
                    'id' => "{$name}-dropdown",
                ],
                'childrenAttributes' => [
                    'class' => 'dropdown-menu text-small shadow dropdown-menu-end',
                    'aria-labelledby' => "{$name}-dropdown",
                ],
            ]);
        }
        $menuItems['manuscripts']->addChild('Browse all MSS', [
            'route' => 'manuscript_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $menuItems['manuscripts']->addChild('Browse MSS by Archive', [
            'route' => 'archive_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $menuItems['manuscripts']->addChild('Browse MSS by Period', [
            'route' => 'period_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $menuItems['manuscripts']->addChild('Browse MSS by Feature', [
            'route' => 'feature_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $menuItems['manuscripts']->addChild('Browse MSS by Themes', [
            'route' => 'theme_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $menuItems['manuscripts']->addChild('Browse MSS by Print Sources', [
            'route' => 'print_source_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $menuItems['manuscripts']->addChild('Browse MSS by Region', [
            'route' => 'region_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        if ($this->hasRole('ROLE_USER')) {
            $menuItems['manuscripts']->addChild('divider1', [
                'label' => '<hr class="dropdown-divider">',
                'extras' => [
                    'safe_label' => true,
                ],
            ]);
            $menuItems['manuscripts']->addChild('Poem Contributions', [
                'route' => 'content_contribution_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item',
                ],
            ]);
            $menuItems['manuscripts']->addChild('Manuscript Poems', [
                'route' => 'manuscript_content_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item',
                ],
            ]);
            $menuItems['manuscripts']->addChild('Manuscript Contributions', [
                'route' => 'manuscript_contribution_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item',
                ],
            ]);
            $menuItems['manuscripts']->addChild('Manuscript Features', [
                'route' => 'manuscript_feature_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item',
                ],
            ]);
        }

        if ($this->hasRole('ROLE_ADMIN')) {
            $menuItems['manuscripts']->addChild('divider2', [
                'label' => '<hr class="dropdown-divider">',
                'extras' => [
                    'safe_label' => true,
                ],
            ]);
            $menuItems['manuscripts']->addChild('Links', [
                'route' => 'nines_media_link_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item',
                ],
            ]);
        }
        $menuItems['people']->addChild('Browse all People', [
            'route' => 'person_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $menuItems['people']->addChild('Browse all Coteries', [
            'route' => 'coterie_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $menuItems['people']->addChild('Browse Poem Roles', [
            'route' => 'content_role_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $menuItems['people']->addChild('Browse Manuscript Contributions', [
            'route' => 'manuscript_role_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);
        $menuItems['poems']->addChild('Browse all Poems', [
            'route' => 'content_index',
            'linkAttributes' => [
                'class' => 'dropdown-item',
            ],
        ]);

        return $menu;
    }

    public function footerMenu(array $options) : ItemInterface {
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
        $menu->addChild('Manuscript Contributions', [
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
