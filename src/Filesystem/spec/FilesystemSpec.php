<?php

namespace spec\Venta\Filesystem;

use League\Flysystem\FilesystemInterface;
use PhpSpec\ObjectBehavior;
use Venta\Contracts\Filesystem\Filesystem;
use Venta\Contracts\Filesystem\Metadata;

class FilesystemSpec extends ObjectBehavior
{

    function let(FilesystemInterface $flysystem)
    {
        $this->beConstructedWith($flysystem);
    }

    function it_can_append_and_prepend(FilesystemInterface $flysystem)
    {
        $flysystem->has('path/to/file')->willReturn(true);
        $flysystem->read('path/to/file')->willReturn('file');
        $flysystem->put('path/to/file', 'file_append',[])->willReturn(true);
        $flysystem->put('path/to/file', 'prepend_file',[])->willReturn(true);

        $this->append('path/to/file', '_append')->shouldBe(true);
        $this->prepend('path/to/file', 'prepend_')->shouldBe(true);

        $flysystem->has('path/to/file')->shouldHaveBeenCalled();
        $flysystem->read('path/to/file')->shouldHaveBeenCalled();
        $flysystem->put('path/to/file', 'file_append',[])->shouldHaveBeenCalled();
        $flysystem->put('path/to/file', 'prepend_file',[])->shouldHaveBeenCalled();
    }

    function it_can_create_delete_directories(FilesystemInterface $flysystem)
    {
        $flysystem->createDir('path/to/dir', [])->willReturn(true);
        $flysystem->deleteDir('path/to/dir')->willReturn(true);

        $this->createDir('path/to/dir');
        $this->deleteDir('path/to/dir');

        $flysystem->createDir('path/to/dir', [])->shouldHaveBeenCalled();
        $flysystem->deleteDir('path/to/dir')->shouldHaveBeenCalled();

    }

    function it_can_list(FilesystemInterface $flysystem)
    {
        $file = [
            'type' => Metadata::TYPE_FILE, 'path' => '/path/to/list/file', 'size' => 123,
        ];
        $dir = [
            'type' => Metadata::TYPE_DIR, 'path' => '/path/to/list/dir',
        ];
        $flysystem->listContents('path/to/list', false)->willReturn([$file, $dir]);

        $list = $this->list('path/to/list', false);
        $list->shouldHaveCount(2);
        $list->shouldHaveValue(new \Venta\Filesystem\Metadata($file));
        $list->shouldHaveValue(new \Venta\Filesystem\Metadata($dir));

        $dirs = $this->listDirectories('path/to/list', false);
        $dirs->shouldHaveCount(1);
        $dirs->shouldHaveValue(new \Venta\Filesystem\Metadata($dir));
        $dirs->shouldNotHaveValue(new \Venta\Filesystem\Metadata($file));

        $files = $this->listFiles('path/to/list', false);
        $files->shouldHaveCount(1);
        $files->shouldNotHaveValue(new \Venta\Filesystem\Metadata($dir));
        $files->shouldHaveValue(new \Venta\Filesystem\Metadata($file));

        $flysystem->listContents('path/to/list', false)->shouldHaveBeenCalledTimes(3);
    }

    function it_can_read_write_delete_copy_move_exist(FilesystemInterface $flysystem)
    {
        $flysystem->read('path/to/file')->willReturn('contents');
        $flysystem->put('path/to/file', 'contents', [])->willReturn(true);
        $flysystem->delete('path/to/file')->willReturn(true);
        $flysystem->copy('path/to/original', 'path/to/copy')->willReturn(true);
        $flysystem->rename('path/to/file', 'path/to/move')->willReturn(true);
        $flysystem->has('path/to/file')->willReturn(true);

        $this->read('path/to/file')->shouldBe('contents');
        $this->write('path/to/file', 'contents')->shouldBe(true);
        $this->delete('path/to/file')->shouldBe(true);
        $this->copy('path/to/original', 'path/to/copy')->shouldBe(true);
        $this->move('path/to/file', 'path/to/move')->shouldBe(true);
        $this->exists('path/to/file')->shouldBe(true);

        $flysystem->read('path/to/file')->shouldHaveBeenCalled();
        $flysystem->put('path/to/file', 'contents', [])->shouldHaveBeenCalled();
        $flysystem->delete('path/to/file')->shouldHaveBeenCalled();
        $flysystem->copy('path/to/original', 'path/to/copy')->shouldHaveBeenCalled();
        $flysystem->rename('path/to/file', 'path/to/move')->shouldHaveBeenCalled();
        $flysystem->has('path/to/file')->shouldHaveBeenCalled();
    }

    function it_can_retrieve_metadata(FilesystemInterface $flysystem)
    {
        $meta = [
            'type' => Metadata::TYPE_FILE,
            'size' => 123,
            'mimetype' => 'mime/type',
        ];
        $flysystem->getMetadata('path/to/file')->willReturn($meta);

        $this->metadata('path/to/file')->shouldBeLike(new \Venta\Filesystem\Metadata($meta));

        $flysystem->getMetadata('path/to/file')->shouldHaveBeenCalled();
    }

    function it_is_initializable()
    {
        $this->shouldImplement(Filesystem::class);
    }

    /**
     * @inheritDoc
     */
    public function getMatchers()
    {
        return [
            'haveValue' => function ($subject, $value) {
                return in_array($value, $subject);
            },
        ];
    }


}
