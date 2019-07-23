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

        if ($this->hasRole('ROLE_USER')) {
            $divider = $browse->addChild('divider', array(
                'label' => '',
            ));
            $divider->setAttributes(array(
                'role' => 'separator',
                'class' => 'divider',
            ));
        }

        return $menu;
    }

}
