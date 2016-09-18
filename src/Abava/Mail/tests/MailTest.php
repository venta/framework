<?php

use PHPUnit\Framework\TestCase;

/**
 * Class MailTest  */
class MailTest extends TestCase
{
    protected $config = [
        'mailer' => [
            'transport' => 'mail',
            'spool' => [
                'type' => 'file',
                'path' => 'spool',
            ],
        ],
    ];

    protected $mailer;

    public function __construct()
    {
        $config = new \Abava\Config\Config($this->config);
        $eventManager = new \Abava\Event\EventManager();
        $this->mailer = new Abava\Mail\Mailer($config, $eventManager);
    }

    /**
     * @test
     * @expectedException \Abava\Mail\Exception\UnknownTransportException
     */
    public function attemptToUseNonconfigureTransportThrowsException()
    {
        $this->mailer->withTransport('gmail');
    }

    /**
     * @test
     * @expectedException \Abava\Mail\Exception\TransportException
     */
    public function attemptToUseUnconfiguredSpoolThrowException()
    {
        $array = [
            'mailer' => [
                'transport' => 'mail',
            ],
        ];
        $config = new \Abava\Config\Config($array);
        $eventManager = new \Abava\Event\EventManager();
        $mailer = new Abava\Mail\Mailer($config, $eventManager);
        $mailer->spoolWithTransport();
    }

    /**
     * @test
     */
    public function configuredTransportsCanBeObtained()
    {
        $array = [
            'mailer' => [
                'mail' => [
                    'transport' => 'mail',
                ],
                'smtp' => [
                    'transport' => 'smtp',
                    'host' => 'localhost',
                ],
            ],
        ];

        $config = new \Abava\Config\Config($array);
        $eventManager = new \Abava\Event\EventManager();
        $mailer = new \Abava\Mail\Mailer($config, $eventManager);

        $this->assertInstanceOf(\Swift_Transport_MailTransport::class, $mailer->withTransport('mail')->getTransport());
        $this->assertInstanceOf(\Swift_Transport_SmtpAgent::class, $mailer->withTransport('smtp')->getTransport());
    }

    /**
     * @test
     */
    public function defaultFromAndToCanBeSet()
    {
        $array = [
            'mailer' => [
                'transport' => 'mail',
                'from' => 'fromTeam@ventacommerce.com',
                'to' => 'toTeam@ventacommerce.com',
            ],
        ];
        $config = new \Abava\Config\Config($array);
        $eventManager = new \Abava\Event\EventManager();
        $mailer = new Abava\Mail\Mailer($config, $eventManager);
        $this->assertEquals(['fromTeam@ventacommerce.com' => null], $mailer->getMessageBuilder()->getFrom());
        $this->assertEquals(['toTeam@ventacommerce.com' => null], $mailer->getMessageBuilder()->getTo());
    }

    /**
     * @test
     */
    public function defaultFromToCanBeArrays()
    {
        $array = [
            'mailer' => [
                'transport' => 'mail',
                'from' => [
                    'fromTeam@ventacommerce.com' => 'team',
                    'fromSecondTeam@ventacommerce.com',
                ],
                'to' => [
                    'toTeam@ventacommerce.com' => 'other team',
                    'toSecondTeam@ventacommerce.com',
                ],
            ],
        ];
        $config = new \Abava\Config\Config($array);
        $eventManager = new \Abava\Event\EventManager();
        $mailer = new Abava\Mail\Mailer($config, $eventManager);

        $this->assertEquals([
            'fromTeam@ventacommerce.com' => 'team',
            'fromSecondTeam@ventacommerce.com' => null,
        ], $mailer->getMessageBuilder()->getFrom());
        $this->assertEquals([
            'toTeam@ventacommerce.com' => 'other team',
            'toSecondTeam@ventacommerce.com' => null,
        ], $mailer->getMessageBuilder()->getTo());
    }

    /**
     * @test
     */
    public function defaultFromToCanBeRedefined()
    {
        $array = [
            'mailer' => [
                'transport' => 'mail',
                'from' => [
                    'fromTeam@ventacommerce.com' => 'team',
                    'fromSecondTeam@ventacommerce.com',
                ],
                'to' => [
                    'toTeam@ventacommerce.com' => 'other team',
                    'toSecondTeam@ventacommerce.com',
                ],
            ],
        ];
        $config = new \Abava\Config\Config($array);
        $eventManager = new \Abava\Event\EventManager();
        $mailer = new Abava\Mail\Mailer($config, $eventManager);
        $message = $mailer->getMessageBuilder()
                          ->setTo('venta@ventacommerce.com')
                          ->setFrom('fromVenta@ventacommerce.com');

        $this->assertEquals(['venta@ventacommerce.com' => null], $message->getTo());
        $this->assertEquals(['fromVenta@ventacommerce.com' => null], $message->getFrom());
    }

    /**
     * @test
     */
    public function gmailIsAutoConfigured()
    {
        $array = [
            'mailer' => [
                'transport' => 'gmail',
                'username' => 'user',
                'password' => 'password',
            ],
        ];
        $config = new \Abava\Config\Config($array);
        $eventManager = new \Abava\Event\EventManager();
        $mailer = new \Abava\Mail\Mailer($config, $eventManager);

        $this->assertInstanceOf(\Swift_Transport_SmtpAgent::class, $mailer->withTransport()->getTransport());
    }

    /**
     * @test
     */
    public function mailerCanBeContructed()
    {
        $config = new \Abava\Config\Config($this->config);
        $eventManager = new \Abava\Event\EventManager();

        $this->assertInstanceOf(Abava\Mail\Mailer::class, new Abava\Mail\Mailer($config, $eventManager));
    }

    /**
     * @test
     */
    public function mailerIsEnabledByDefault()
    {
        $array = [
            'mailer' => [
                'transport' => 'mail',
            ],
        ];
        $config = new \Abava\Config\Config($array);
        $eventManager = new \Abava\Event\EventManager();
        $mailer = new Abava\Mail\Mailer($config, $eventManager);

        $this->assertFalse($mailer->isDisabled());
    }

    /**
     * @test
     * @expectedException \Abava\Mail\Exception\TransportException
     */
    public function mailerNeedsAtLeastOneTransport()
    {
        $array = [
            'mailer' => [
                'disable_delivery' => 'false',
            ],
        ];

        $config = new \Abava\Config\Config($array);
        $eventManager = new \Abava\Event\EventManager();
        new Abava\Mail\Mailer($config, $eventManager);
    }

    /**
     * @test
     */
    public function messageBuilderCanBeRetrieved()
    {
        $message = $this->mailer->getMessageBuilder();

        $this->assertInstanceOf(\Swift_Message::class, $message);
    }

    /**
     * @test
     */
    public function nullTransportUsedWhenDisabled()
    {
        $array = [
            'mailer' => [
                'disable_delivery' => true,
            ],
        ];
        $config = new \Abava\Config\Config($array);
        $eventManager = new \Abava\Event\EventManager();
        $mailer = new Abava\Mail\Mailer($config, $eventManager);

        $this->assertTrue($mailer->isDisabled());
        $this->assertInstanceOf(\Swift_Transport_NullTransport::class, $mailer->withTransport()->getTransport());
    }

    /**
     * @test
     */
    public function spoolCanUseMemoryStorage()
    {
        $array = [
            'mailer' => [
                'mail' => [
                    'transport' => 'mail',
                ],
                'spool' => [
                    'type' => 'memory',
                ],
            ]
        ];

        $config = new \Abava\Config\Config($array);
        $eventManager = new \Abava\Event\EventManager();
        $mailer = new \Abava\Mail\Mailer($config, $eventManager);
        $this->assertInstanceOf(Swift_Transport_SpoolTransport::class, $mailer->spoolWithTransport()->getTransport());
    }

    /**
     * @test
     * @expectedException \Abava\Mail\Exception\TransportException
     */
    public function fileSpoolPathIsRequired()
    {
        $array = [
            'mailer' => [
                'mail' => [
                    'transport' => 'mail',
                ],
                'spool' => [
                    'type' => 'file',
                ],
            ]
        ];

        $config = new \Abava\Config\Config($array);
        $eventManager = new \Abava\Event\EventManager();
        $mailer = new \Abava\Mail\Mailer($config, $eventManager);
        $this->assertInstanceOf(Swift_Transport_SpoolTransport::class, $mailer->spoolWithTransport()->getTransport());
    }

    /**
     * @test
     * @expectedException \Abava\Mail\Exception\UnknownTransportException
     */
    public function unknownSpoolTypeProducesException()
    {
        $array = [
            'mailer' => [
                'mail' => [
                    'transport' => 'mail',
                ],
                'spool' => [
                    'type' => 'unknown',
                ],
            ]
        ];

        $config = new \Abava\Config\Config($array);
        $eventManager = new \Abava\Event\EventManager();
        $mailer = new \Abava\Mail\Mailer($config, $eventManager);
        $this->assertInstanceOf(Swift_Transport_SpoolTransport::class, $mailer->spoolWithTransport()->getTransport());
    }

    /**
     * @test
     */
    public function spoolRealTransportIsSet()
    {
        $this->assertInstanceOf(Swift_Transport_SpoolTransport::class,
            $this->mailer->spoolWithTransport()->getTransport());
    }

    /**
     * @test
     */
    public function spoolTransportCanBeDefinedInConfig()
    {
        $this->assertInstanceOf(\Swift_Transport_SpoolTransport::class, $this->mailer->getSpoolTransport());
    }

    /**
     * @test
     * @expectedException \Abava\Mail\Exception\TransportException
     */
    public function undefiendTransportThrowsException()
    {
        $array = [
            'mailer' => [
                'smtp' => [
                    'host' => 'localhost',
                ],
            ],
        ];

        $config = new \Abava\Config\Config($array);
        $eventManager = new \Abava\Event\EventManager();
        new \Abava\Mail\Mailer($config, $eventManager);
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function undefinedMailerConfigThrowsException()
    {
        $array = [
            'db_host' => 'localhost',
        ];
        $config = new \Abava\Config\Config($array);
        $eventManager = new \Abava\Event\EventManager();
        new \Abava\Mail\Mailer($config, $eventManager);
    }

    /**
     * @test
     * @expectedException \Abava\Mail\Exception\UnknownTransportException
     */
    public function unknownTransprotTypeThrowsException()
    {
        $array = [
            'mailer' => [
                'mail' => [
                    'transport' => 'unknown',
                ],
            ],
        ];

        $config = new \Abava\Config\Config($array);
        $eventManager = new \Abava\Event\EventManager();
        new \Abava\Mail\Mailer($config, $eventManager);
    }

    /**
     * @test
     * @expectedException \Abava\Mail\Exception\TransportException
     */
    public function gmailRequiresserAndPassword()
    {
        $array = [
            'mailer' => [
                'mail' => [
                    'transport' => 'gmail',
                ],
            ],
        ];

        $config = new \Abava\Config\Config($array);
        $eventManager = new \Abava\Event\EventManager();
        new \Abava\Mail\Mailer($config, $eventManager);
    }

    /**
     * @test
     * @expectedException \Abava\Mail\Exception\TransportException
     */
    public function smtpRequiresHost()
    {
        $array = [
            'mailer' => [
                'mail' => [
                    'transport' => 'smtp',
                    'port' => '3333'
                ],
            ],
        ];

        $config = new \Abava\Config\Config($array);
        $eventManager = new \Abava\Event\EventManager();
        new \Abava\Mail\Mailer($config, $eventManager);
    }

    /**
     * @test
     */
    public function spoolCanBeExplicitlyDisabled()
    {
        $array = [
            'mailer' => [
                'mail' => [
                    'transport' => 'mail',
                ],
                'spool' => false
            ],
        ];

        $config = new \Abava\Config\Config($array);
        $eventManager = new \Abava\Event\EventManager();
        new \Abava\Mail\Mailer($config, $eventManager);
    }
}