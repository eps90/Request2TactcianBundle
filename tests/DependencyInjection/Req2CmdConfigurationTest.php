<?php
declare(strict_types=1);

namespace Eps\Req2CmdBundle\Tests\DependencyInjection;

use Eps\Req2CmdBundle\DependencyInjection\Req2CmdConfiguration;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Req2CmdConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration(): ConfigurationInterface
    {
        return new Req2CmdConfiguration();
    }

    /**
     * @test
     */
    public function itShouldSetDefaultExtractorConfig(): void
    {
        $inputConfig = [];
        $expectedConfig = [
            'extractor' => [
                'service_id' => 'eps.req2cmd.extractor.serializer'
            ]
        ];
        $this->assertProcessedConfigurationEquals(
            [
                $inputConfig
            ],
            $expectedConfig,
            'extractor'
        );
    }

    /**
     * @test
     */
    public function itShouldBeAbleToChangeExtractorConfigToBuiltIn(): void
    {
        $inputConfig = [
            'extractor' => 'jms_serializer'
        ];
        $expectedConfig = [
            'extractor' => [
                'service_id' => 'eps.req2cmd.extractor.jms_serializer'
            ]
        ];
        $this->assertProcessedConfigurationEquals(
            [
                $inputConfig
            ],
            $expectedConfig,
            'extractor'
        );
    }

    /**
     * @test
     */
    public function itShouldBeAbleToDefineOwnServiceForExtractor(): void
    {
        $inputConfig = [
            'extractor' => [
                'service_id' => 'app.my_extractor'
            ]
        ];
        $expectedConfig = [
            'extractor' => [
                'service_id' => 'app.my_extractor'
            ]
        ];
        $this->assertProcessedConfigurationEquals(
            [$inputConfig],
            $expectedConfig,
            'extractor'
        );
    }

    /**
     * @test
     */
    public function itShouldSetTacticianAsDefaultCommandBus(): void
    {
        $inputConfig = [];
        $expectedConfig = [
            'command_bus' => [
                'service_id' => 'eps.req2cmd.command_bus.tactician',
                'name' => 'default'
            ]
        ];
        $this->assertProcessedConfigurationEquals(
            [$inputConfig],
            $expectedConfig,
            'command_bus'
        );
    }

    /**
     * @test
     */
    public function itShouldBeAbleToSetOtherBuiltInCommandBus(): void
    {
        $inputConfig = [
            'command_bus' => 'broadway'
        ];
        $expectedConfig = [
            'command_bus' => [
                'service_id' => 'eps.req2cmd.command_bus.broadway'
            ]
        ];
        $this->assertProcessedConfigurationEquals(
            [$inputConfig],
            $expectedConfig,
            'command_bus'
        );
    }

    /**
     * @test
     */
    public function itShouldAllowToAddCustomCommandBus(): void
    {
        $inputConfig = [
            'command_bus' => [
                'service_id' => 'app.command_bus.custom'
            ]
        ];
        $expectedConfig = [
            'command_bus' => [
                'service_id' => 'app.command_bus.custom'
            ]
        ];

        $this->assertProcessedConfigurationEquals(
            [$inputConfig],
            $expectedConfig,
            'command_bus'
        );
    }

    /**
     * @test
     */
    public function itShouldBeAbleToSetTacticianCommandBusType(): void
    {
        $inputConfig = [
            'command_bus' => [
                'name' => 'queued'
            ]
        ];
        $expectedConfig = [
            'command_bus' => [
                'service_id' => 'eps.req2cmd.command_bus.tactician',
                'name' => 'queued'
            ]
        ];

        $this->assertProcessedConfigurationEquals(
            [$inputConfig],
            $expectedConfig,
            'command_bus'
        );
    }

    /**
     * @test
     */
    public function itShouldUnsetCommandBusNameWhenItIsNotTactician(): void
    {
        $inputConfig = [
            'command_bus' => [
                'service_id' => 'my.custom.bus',
                'name' => 'blablabla'
            ]
        ];
        $expectedConfig = [
            'command_bus' => [
                'service_id' => 'my.custom.bus'
            ]
        ];

        $this->assertProcessedConfigurationEquals(
            [$inputConfig],
            $expectedConfig,
            'command_bus'
        );
    }

    /**
     * @test
     */
    public function itShouldDefineDefaultListenerPriority(): void
    {
        $inputConfig = [];
        $expectedConfig = [
            'listeners' => [
                'extractor' => [
                    'enabled' => true,
                    'priority' => 0
                ]
            ]
        ];

        $this->assertProcessedConfigurationEquals(
            [$inputConfig],
            $expectedConfig,
            'listeners'
        );
    }

    /**
     * @test
     */
    public function itShouldBeAbleToSetListenerPriority(): void
    {
        $inputConfig = [
            'listeners' => [
                'extractor' => [
                    'priority' => 128
                ]
            ]
        ];
        $expectedConfig = [
            'listeners' => [
                'extractor' => [
                    'enabled' => true,
                    'priority' => 128
                ]
            ]
        ];

        $this->assertProcessedConfigurationEquals(
            [$inputConfig],
            $expectedConfig,
            'listeners'
        );
    }

    /**
     * @test
     */
    public function itShouldAllowToDisableAListener(): void
    {
        $inputConfig = [
            'listeners' => [
                'extractor' => [
                    'enabled' => false
                ]
            ]
        ];

        $this->assertConfigurationIsValid(
            [$inputConfig],
            'listeners'
        );
    }

    /**
     * @test
     */
    public function itShouldAllowToDisableAListenerUsingShorthandMethod(): void
    {
        $inputConfig = [
            'listeners' => [
                'extractor' => false
            ]
        ];
        $expectedConfig = [
            'listeners' => [
                'extractor' => [
                    'enabled' => false,
                    'priority' => 0
                ]
            ]
        ];

        $this->assertProcessedConfigurationEquals(
            [$inputConfig],
            $expectedConfig,
            'listeners'
        );
    }
}
