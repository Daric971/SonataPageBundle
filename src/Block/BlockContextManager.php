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

use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BlockContextManager extends AbstractBlockContextManager
{
    protected function configureSettings(OptionsResolver $optionsResolver, BlockInterface $block): void
    {
        $optionsResolver->setDefaults([
            'manager' => false,
            'page_id' => false,
        ]);

        $optionsResolver
            ->addAllowedTypes('manager', ['string', 'bool'])
            ->addAllowedTypes('page_id', ['int', 'string', 'bool']);

        $optionsResolver->setRequired([
            'manager',
            'page_id',
        ]);
    }
}
