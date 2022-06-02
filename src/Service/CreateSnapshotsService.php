<?php

namespace Sonata\PageBundle\Service;

use Sonata\PageBundle\Model\PageInterface;
use Sonata\PageBundle\Model\PageManagerInterface;
use Sonata\PageBundle\Model\SnapshotInterface;
use Sonata\PageBundle\Model\SnapshotManagerInterface;
use Sonata\PageBundle\Model\TransformerInterface;

class CreateSnapshotsService implements CreateSnapshotsFromPageInterface
{
    protected SnapshotManagerInterface $snapshotManager;

    protected PageManagerInterface $pageManager;

    protected TransformerInterface $transformer;

    public function __construct(
        SnapshotManagerInterface $snapshotManager,
        PageManagerInterface $pageManager,
        TransformerInterface $transformer
    ) {
        $this->snapshotManager = $snapshotManager;
        $this->pageManager = $pageManager;
        $this->transformer = $transformer;
    }

    /**
     * @param iterable<PageInterface> $pages
     * @return iterable<SnapshotInterface>
     */
    public function createFromPages(iterable $pages): iterable
    {
        $entityManager = $this->snapshotManager->getEntityManager();

        // start a transaction
        $entityManager->beginTransaction();

        foreach ($pages as $page) {
            yield $this->createByPage($page);
        }

        // commit the changes
        $entityManager->commit();
    }

    protected function createByPage(PageInterface $page): SnapshotInterface
    {
        // creating snapshot
        $snapshot = $this->transformer->create($page);

        // update the page status
        $page->setEdited(false);
        $this->pageManager->save($page);

        // save the snapshot
        $this->snapshotManager->save($snapshot);
        $this->snapshotManager->enableSnapshots([$snapshot]);

        return $snapshot;
    }
}
