<?php

namespace Sonata\PageBundle\Tests\Command;

use Sonata\PageBundle\Model\SiteInterface;
use Sonata\PageBundle\Model\SiteManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateSnapshotsCommandTest extends KernelTestCase
{
    /**
     * @test
     * @testdox It's required to pass some site parameter
     */
    public function siteRequired(): void
    {
        //Mocks
        $siteMock = $this->createMock(SiteInterface::class);
        $siteMock
            ->method('getId')
            ->willReturn(1);
        $siteMock
            ->method('getName')
            ->willReturn('foo');
        $siteMock
            ->method('getUrl')
            ->willReturn('https://bar.baz');

        $siteManagerMock = $this->createMock(SiteManagerInterface::class);
        $siteManagerMock
            ->expects($this->once())
            ->method('findBy')
            ->willReturn([$siteMock]);

        // Setup SymfonyKernel
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $container->set('sonata.page.manager.site', $siteManagerMock);
        $application = new Application($kernel);

        //Command
        $command = $application->find('sonata:page:create-snapshots');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);
        $output = $commandTester->getDisplay();

        //Asserts
        $this->assertStringContainsString('Please provide an --site=SITE_ID option or the --site=all', $output);
        $this->assertStringContainsString('1 - foo', $output);
        $this->assertStringContainsString('https://bar.baz', $output);
    }
}
