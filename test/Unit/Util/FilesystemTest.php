<?php

declare(strict_types=1);

namespace GrumPHPTest\Unit\Util;

use GrumPHP\Configuration\GrumPHP;
use GrumPHP\Util\Filesystem;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use Symfony\Component\Filesystem\Tests\FilesystemTestCase;

class FilesystemTest extends FilesystemTestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $virtualFS;

    /**
     * @var GrumPHP|ObjectProphecy
     */
    private $config;

    /**
     * @var Filesystem
     */
    private $filesystem;

    protected function setUp()
    {
        parent::setUp();
        $this->config = $this->prophesize(GrumPHP::class);
        $this->filesystem = new Filesystem($this->config->reveal());

        $this->config->getConfigFile()->willReturn(vfsStream::path('project/grumphp.yml'));
        $this->config->getGitDir()->willReturn('.');
    }

    /** @test */
    public function it_extends_symfony_filesystem(): void
    {
        $this->assertInstanceOf(SymfonyFilesystem::class, $this->filesystem);
    }

    /** @test */
    public function it_can_load_file_contents(): void
    {
        $file = vfsStream::newFile('helloworld.txt')
            ->at($this->virtualFS)
            ->setContent($content = 'hello world')
            ->url();

        $this->assertEquals(
            $content,
            $this->filesystem->readFromFileInfo(new \SplFileInfo($file))
        );
    }

    /** @test */
    public function it_knows_the_git_directory(): void
    {
        $this->assertSame(
            getcwd(),
            $this->filesystem->getGitDir()
        );
    }

    /** @test */
    public function it_knows_the_project_directory(): void
    {
        $this->assertSame(
            realpath(PROJECT_BASE_PATH),
            $this->filesystem->getProjectDir()
        );
    }

    /**
     * @test
     * @dataProvider provideRelativeProjectDirCases
     */
    public function it_can_load_relative_project_dir_path(string $projectDir, string $gitDir, string $expected): void
    {
        $this->config->getConfigFile()->willReturn($projectDir.DIRECTORY_SEPARATOR.'grumphp.yml');
        $this->config->getGitDir()->willReturn($gitDir);

        $this->assertSame($expected, $this->filesystem->getRelativeProjectDir());
    }

    public function provideRelativeProjectDirCases(): array
    {
        return [
            [
                getcwd().'/home',
                '.',
                ''
            ],
            [
                getcwd().'/home/project',
                '..',
                'project'
            ],
            [
                getcwd().'/home/project/hello/world',
                '../../..',
                'project/hello/world'
            ],
        ];
    }
}
