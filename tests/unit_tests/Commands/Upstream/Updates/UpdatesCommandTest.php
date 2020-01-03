<?php

namespace Pantheon\Terminus\UnitTests\Commands\Upstream\Updates;

use Pantheon\Terminus\Models\Upstream;
use Pantheon\Terminus\Models\UpstreamStatus;
use Pantheon\Terminus\UnitTests\Commands\CommandTestCase;

/**
 * Class UpdatesCommandTest
 * @package Pantheon\Terminus\UnitTests\Commands\Upstream\Updates
 */
abstract class UpdatesCommandTest extends CommandTestCase
{
    /**
     * @var Upstream
     */
    protected $upstream;
    /**
     * @var UpstreamStatus
     */
    protected $upstream_status;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->upstream = $this->createMock(Upstream::class);
        $this->upstream_status = $this->createMock(UpstreamStatus::class);

        $this->environment->method('getUpstreamStatus')
            ->with()
            ->willReturn($this->upstream_status);
        $this->site->method('getUpstream')
            ->with()
            ->willReturn($this->upstream);
    }
}
