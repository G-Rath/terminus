<?php

namespace Pantheon\Terminus\UnitTests\Collections;

use Pantheon\Terminus\Collections\Domains;
use Pantheon\Terminus\Collections\Workflows;
use Pantheon\Terminus\Models\Environment;
use Pantheon\Terminus\Models\Site;
use Pantheon\Terminus\Models\Workflow;

/**
 * Class DomainsTest
 * Testing class for Pantheon\Terminus\Collections\Domains
 * @package Pantheon\Terminus\UnitTests\Collections
 */
class DomainsTest extends CollectionTestCase
{
    /**
     * @var Domains
     */
    protected $collection;
    /**
     * @var Environment
     */
    protected $environment;
    /**
     * @var Site
     */
    protected $site;
    /**
     * @var string
     */
    protected $url;
    /**
     * @var Workflows
     */
    protected $workflows;

    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->environment = $this->createMock(Environment::class);
        $this->environment->id = 'dev';
        $this->site = $this->createMock(Site::class);
        $this->site->id = 'site id';
        $this->url = "sites/{$this->site->id}/environments/{$this->environment->id}/domains";
        $this->workflow = $this->createMock(Workflow::class);
        $this->workflows = $this->createMock(Workflows::class);

        $this->environment->method('getSite')
            ->willReturn($this->site);
        $this->environment->method('getWorkflows')
            ->willReturn($this->workflows);

        $this->collection = new Domains(['environment' => $this->environment,]);
        $this->collection->setRequest($this->request);
        $this->collection->setContainer($this->container);
    }

    /**
     * Tests the Domains::create() function
     */
    public function testCreate()
    {
        $domain = 'dev.example.com';
        $this->request->expects($this->once())
            ->method('request')
            ->with("{$this->url}/$domain", ['method' => 'put',]);

        $this->assertNull($this->collection->create($domain));
    }

    /**
     * Tests the Domains::fetchWithRecommendations() function
     */
    public function testFetchWithRecommendations()
    {
        $dummy_data = [
            (object)['id' => 'domain.com', 'type' => 'custom',]
        ];
        $this->request->expects($this->once())
            ->method('request')
            ->with(
                $this->url,
                [
                    'options' => ['method' => 'get',],
                    'query' => ['hydrate' => ['as_list', 'recommendations',],],
                ]
            )
            ->willReturn(['data' => $dummy_data,]);

        $out = $this->collection->fetchWithRecommendations();
        $this->assertEquals($this->collection, $out);
        $this->assertEquals($this->collection->getData(), $dummy_data);
    }
}
