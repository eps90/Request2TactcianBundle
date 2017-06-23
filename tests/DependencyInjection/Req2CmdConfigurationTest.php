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
        $expectedProcessedConfig = [
            'extractor' => [
                'service_id' => 'eps.req2cmd.extractor.serializer'
            ]
        ];
        $this->assertProcessedConfigurationEquals(
            [
                $inputConfig
            ],
            $expectedProcessedConfig,
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
        $expectedProcessedConfig = [
            'extractor' => [
                'service_id' => 'eps.req2cmd.extractor.jms_serializer'
            ]
        ];
        $this->assertProcessedConfigurationEquals(
            [
                $inputConfig
            ],
            $expectedProcessedConfig,
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
        $expectedProcessedConfig = [
            'extractor' => [
                'service_id' => 'app.my_extractor'
            ]
        ];
        $this->assertProcessedConfigurationEquals(
            [$inputConfig],
            $expectedProcessedConfig,
            'extractor'
        );
    }

    /**
     * @test
     */
    public function itShouldHaveDefaultParamMappers(): void
    {
        $inputConfig = [];
        $expectedProcessedConfig = [
            'param_mappers' => [
                'eps.req2cmd.param_mapper.path'
            ]
        ];
        $this->assertProcessedConfigurationEquals(
            [$inputConfig],
            $expectedProcessedConfig,
            'param_mappers'
        );
    }

    /**
     * @test
     */
    public function itShouldBeAbleToDefineMapOfParamMappersServices(): void
    {
        $inputConfig = [
            'param_mappers' => [
                'app.mapper.first',
                'app.mapper.second'
            ]
        ];
        $expectedConfig = [
            'param_mappers' => [
                'app.mapper.first',
                'app.mapper.second'
            ]
        ];

        $this->assertProcessedConfigurationEquals(
            [$inputConfig],
            $expectedConfig,
            'param_mappers'
        );
    }
}
