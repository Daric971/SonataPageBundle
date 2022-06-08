<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PageBundle\Block;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\Provider\MenuProviderInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Meta\Metadata;
use Sonata\PageBundle\CmsManager\CmsManagerSelectorInterface;
use Sonata\PageBundle\Model\PageInterface;
use Sonata\SeoBundle\Block\Breadcrumb\BaseBreadcrumbMenuBlockService;
use Twig\Environment;

/**
 * BlockService for homepage breadcrumb.
 *
 * @author Sylvain Deloux <sylvain.deloux@ekino.com>
 */
final class BreadcrumbBlockService extends BaseBreadcrumbMenuBlockService
{
    private CmsManagerSelectorInterface $cmsSelector;

    private string $context;

    private MenuProviderInterface $menuProvider;

    private string $name;

    public function __construct(string $context, string $name, Environment $twig, MenuProviderInterface $menuProvider, FactoryInterface $factory, CmsManagerSelectorInterface $cmsSelector)
    {
        parent::__construct($twig, $factory);

        $this->name = $name;
        $this->context = $context;
        $this->menuProvider = $menuProvider;
        $this->cmsSelector = $cmsSelector;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getBlockMetadata($code = null)
    {
        return new Metadata($this->getName(), (null !== $code ? $code : $this->getName()), null, 'SonataPageBundle', [
            'class' => 'fa fa-bars',
        ]);
    }

    public function handleContext(string $context): bool
    {
        return $this->context === $context;
    }

    protected function getMenu(BlockContextInterface $blockContext): ItemInterface
    {
        $blockContext->setSetting('include_homepage_link', false);

        $menu = parent::getMenu($blockContext);

        $page = $this->getCurrentPage();

        if (!$page) {
            return $menu;
        }

        $parents = $page->getParents();

        foreach ($parents as $parent) {
            if ($parent->isError()) {
                continue;
            }

            $this->createMenuItem($menu, $parent);
        }

        if (!$page->isError()) {
            $this->createMenuItem($menu, $page);
        }

        return $menu;
    }

    private function getCurrentPage(): PageInterface
    {
        $cms = $this->cmsSelector->retrieve();

        return $cms->getCurrentPage();
    }

    private function createMenuItem(ItemInterface $menu, PageInterface $page): void
    {
        $label = $page->getTitle();
        $extras = [];

        if (null === $label) {
            $label = $page->getName();

            $extras['translation_domain'] = 'SonataPageBundle';
        }

        $menu->addChild($label, [
            'route' => 'page_slug',
            'routeParameters' => [
                'path' => $page->getUrl(),
            ],
            'extras' => $extras,
        ]);
    }
}
