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
use Sonata\BlockBundle\Block\Service\BlockServiceInterface;
use Sonata\BlockBundle\Block\Service\ContainerBlockService as BaseContainerBlockService;
use Sonata\BlockBundle\Block\Service\EditableBlockService;
use Sonata\BlockBundle\Form\Mapper\FormMapper;
use Sonata\BlockBundle\Meta\MetadataInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\Form\Validator\ErrorElement;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

/**
 * AbstractContainerBlockService.
 *
 * @author Daric DESBONNES <daric.desbonnes@ekino.com>
 */
abstract class AbstractContainerBlockService implements BlockServiceInterface, EditableBlockService
{
    private BaseContainerBlockService $containerBlockService;

    public function __construct(BaseContainerBlockService $containerBlockService)
    {
        $this->containerBlockService = $containerBlockService;
    }

    public function getTwig(): Environment
    {
        return $this->containerBlockService->getTwig();
    }

    public function getContainerBlockService(): BaseContainerBlockService
    {
        return $this->containerBlockService;
    }

    public function execute(BlockContextInterface $blockContext, ?Response $response = null): Response
    {
        return $this->containerBlockService->execute($blockContext, $response);
    }

    public function load(BlockInterface $block): void
    {
        $this->containerBlockService->load($block);
    }

    public function getCacheKeys(BlockInterface $block): array
    {
        return $this->containerBlockService->getCacheKeys($block);
    }

    abstract public function configureSettings(OptionsResolver $resolver): void;

    public function configureEditForm(FormMapper $form, BlockInterface $block): void
    {
        $this->containerBlockService->configureEditForm($form, $block);
    }

    public function configureCreateForm(FormMapper $form, BlockInterface $block): void
    {
        $this->containerBlockService->configureCreateForm($form, $block);
    }

    public function validate(ErrorElement $errorElement, BlockInterface $block): void
    {
        $this->containerBlockService->validate($errorElement, $block);
    }

    public function getMetadata(): MetadataInterface
    {
        return $this->getContainerBlockService()->getMetadata();
    }
}
