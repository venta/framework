<?php

namespace spec\Venta\Filesystem;

use DateTime;
use PhpSpec\ObjectBehavior;
use Venta\Contracts\Filesystem\Metadata;

class MetadataSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $now = new DateTime();
        $this->beConstructedWith([
            'type' => Metadata::TYPE_FILE,
            'path' => 'path/to/file',
            'mimetype' => 'mime/type',
            'size' => 123,
            'timestamp' => $now,
        ]);

        $this->shouldImplement(Metadata::class);
        $this->type()->shouldBe(Metadata::TYPE_FILE);
        $this->path()->shouldBe('path/to/file');
        $this->mimetype()->shouldBe('mime/type');
        $this->size()->shouldBe(123);
        $this->timestamp()->shouldBe($now);
    }

}
