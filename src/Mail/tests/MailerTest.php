<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;

class MailerTest extends TestCase
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
        $config = new \Venta\Config\Config($this->config);
        $eventManager = new \Venta\Event\EventManager();
        $this->mailer = new Venta\Mail\Mailer($config, $eventManager);
    }

    /**
     * @test
     * @expectedException \Venta\Mail\Exception\UnknownTransportException
     */
    public function attemptToUseNonconfigureTransportThrowsException()
    {
        $this->mailer->withTransport('gmail');
    }

    /**
     * @test
     * @expectedException \Venta\Mail\Exception\TransportException
     */
    public function attemptToUseUnconfiguredSpoolThrowException()
    {
        $array = [
            'mailer' => [
                'transport' => 'mail',
            ],
        ];
        $config = new \Venta\Config\Config($array);
        $eventManager = new \Venta\Event\EventManager();
        $mailer = new Venta\Mail\Mailer($config, $eventManager);
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

        $config = new \Venta\Config\Config($array);
        $eventManager = new \Venta\Event\EventManager();
        $mailer = new \Venta\Mail\Mailer($config, $eventManager);

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
        $config = new \Venta\Config\Config($array);
        $eventManager = new \Venta\Event\EventManager();
        $mailer = new Venta\Mail\Mailer($config, $eventManager);
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
        $config = new \Venta\Config\Config($array);
        $eventManager = new \Venta\Event\EventManager();
        $mailer = new Venta\Mail\Mailer($config, $eventManager);

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
        $config = new \Venta\Config\Config($array);
        $eventManager = new \Venta\Event\EventManager();
        $mailer = new Venta\Mail\Mailer($config, $eventManager);
        $message = $mailer->getMessageBuilder()
                          ->setTo('venta@ventacommerce.com')
                          ->setFrom('fromVenta@ventacommerce.com');

        $this->assertEquals(['venta@ventacommerce.com' => null], $message->getTo());
        $this->assertEquals(['fromVenta@ventacommerce.com' => null], $message->getFrom());
    }

    /**
     * @test
     * @expectedException \Venta\Mail\Exception\TransportException
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
            ],
        ];

        $config = new \Venta\Config\Config($array);
        $eventManager = new \Venta\Event\EventManager();
        $mailer = new \Venta\Mail\Mailer($config, $eventManager);
        $this->assertInstanceOf(Swift_Transport_SpoolTransport::class, $mailer->spoolWithTransport()->getTransport());
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
        $config = new \Venta\Config\Config($array);
        $eventManager = new \Venta\Event\EventManager();
        $mailer = new \Venta\Mail\Mailer($config, $eventManager);

        $this->assertInstanceOf(\Swift_Transport_SmtpAgent::class, $mailer->withTransport()->getTransport());
    }

    /**
     * @test
     * @expectedException \Venta\Mail\Exception\TransportException
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

        $config = new \Venta\Config\Config($array);
        $eventManager = new \Venta\Event\EventManager();
        new \Venta\Mail\Mailer($config, $eventManager);
    }

    /**
     * @test
     */
    public function mailerCanBeContructed()
    {
        $config = new \Venta\Config\Config($this->config);
        $eventManager = new \Venta\Event\EventManager();

        $this->assertInstanceOf(Venta\Mail\Mailer::class, new Venta\Mail\Mailer($config, $eventManager));
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
        $config = new \Venta\Config\Config($array);
        $eventManager = new \Venta\Event\EventManager();
        $mailer = new Venta\Mail\Mailer($config, $eventManager);

        $this->assertFalse($mailer->isDisabled());
    }

    /**
     * @test
     * @expectedException \Venta\Mail\Exception\TransportException
     */
    public function mailerNeedsAtLeastOneTransport()
    {
        $array = [
            'mailer' => [
                'disable_delivery' => 'false',
            ],
        ];

        $config = new \Venta\Config\Config($array);
        $eventManager = new \Venta\Event\EventManager();
        new Venta\Mail\Mailer($config, $eventManager);
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
        $config = new \Venta\Config\Config($array);
        $eventManager = new \Venta\Event\EventManager();
        $mailer = new Venta\Mail\Mailer($config, $eventManager);

        $this->assertTrue($mailer->isDisabled());
        $this->assertInstanceOf(\Swift_Transport_NullTransport::class, $mailer->withTransport()->getTransport());
    }

    /**
     * @test
     * @expectedException \Venta\Mail\Exception\TransportException
     */
    public function smtpRequiresHost()
    {
        $array = [
            'mailer' => [
                'mail' => [
                    'transport' => 'smtp',
                    'port' => '3333',
                ],
            ],
        ];

        $config = new \Venta\Config\Config($array);
        $eventManager = new \Venta\Event\EventManager();
        new \Venta\Mail\Mailer($config, $eventManager);
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
                'spool' => false,
            ],
        ];

        $config = new \Venta\Config\Config($array);
        $eventManager = new \Venta\Event\EventManager();
        new \Venta\Mail\Mailer($config, $eventManager);
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
            ],
        ];

        $config = new \Venta\Config\Config($array);
        $eventManager = new \Venta\Event\EventManager();
        $mailer = new \Venta\Mail\Mailer($config, $eventManager);
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
     * @expectedException \Venta\Mail\Exception\TransportException
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

        $config = new \Venta\Config\Config($array);
        $eventManager = new \Venta\Event\EventManager();
        new \Venta\Mail\Mailer($config, $eventManager);
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
        $config = new \Venta\Config\Config($array);
        $eventManager = new \Venta\Event\EventManager();
        new \Venta\Mail\Mailer($config, $eventManager);
    }

    /**
     * @test
     * @expectedException \Venta\Mail\Exception\UnknownTransportException
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
            ],
        ];

        $config = new \Venta\Config\Config($array);
        $eventManager = new \Venta\Event\EventManager();
        $mailer = new \Venta\Mail\Mailer($config, $eventManager);
        $this->assertInstanceOf(Swift_Transport_SpoolTransport::class, $mailer->spoolWithTransport()->getTransport());
    }

    /**
     * @test
     * @expectedException \Venta\Mail\Exception\UnknownTransportException
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

        $config = new \Venta\Config\Config($array);
        $eventManager = new \Venta\Event\EventManager();
        new \Venta\Mail\Mailer($config, $eventManager);
    }
}