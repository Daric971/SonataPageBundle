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

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\BlockContextManager as BaseBlockContextManager;
use Sonata\BlockBundle\Block\BlockContextManagerInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * AbstractBlockContextManager.
 *
 * @author Daric DESBONNES <daric.desbonnes@ekino.com>
 */
abstract class AbstractBlockContextManager implements BlockContextManagerInterface
{
    /** @var BaseBlockContextManager|BlockContextManagerInterface */
    private $blockContextManager;

    private OptionsResolver $optionsResolver;

    /**
     * @param BaseBlockContextManager|BlockContextManagerInterface $blockContextManager
     */
    public function __construct($blockContextManager)
    {
        $this->blockContextManager = $blockContextManager;
        $this->optionsResolver = new OptionsResolver();
    }

    public function getOptionsResolver(): OptionsResolver
    {
        return $this->optionsResolver;
    }

    /**
     * @return BaseBlockContextManager|BlockContextManagerInterface
     */
    public function getBlockContextManager()
    {
        return $this->blockContextManager;
    }

    public function addSettingsByType(string $type, array $settings, bool $replace = false): void
    {
        $this->blockContextManager->addSettingsByType($type, $settings, $replace);
    }

    public function addSettingsByClass(string $class, array $settings, bool $replace = false): void
    {
        $this->blockContextManager->addSettingsByClass($class, $settings, $replace);
    }

    public function get($meta, array $settings = []): BlockContextInterface
    {
        $this->configureSettings($this->optionsResolver, $meta);

        return $this->blockContextManager->get($meta, $meta->getSettings());
    }

    public function exists(string $type): bool
    {
        return $this->blockContextManager->exists($type);
    }

    abstract protected function configureSettings(OptionsResolver $optionsResolver, BlockInterface $block): void;
}
