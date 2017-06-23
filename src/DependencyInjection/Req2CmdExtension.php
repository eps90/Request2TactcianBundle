<?php
declare(strict_types=1);

namespace Eps\Req2CmdBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

final class Req2CmdExtension extends Extension
{
    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Req2CmdConfiguration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('actions.xml');
        $loader->load('extractors.xml');
        $loader->load('listeners.xml');
        $loader->load('param_mappers.xml');

        $this->configureExtractors($config, $container);
        $this->configureParamMappers($config, $container);
    }

    public function getAlias(): string
    {
        return 'req2cmd';
    }

    private function configureExtractors(array $config, ContainerBuilder $container): void
    {
        $extractorId = (string)$config['extractor']['service_id'];
        $container->setAlias('eps.req2cmd.extractor', $extractorId);
    }

    private function configureParamMappers(array $config, ContainerBuilder $container): void
    {
        $mappers = array_map(
            function (string $mapperId) {
                return new Reference($mapperId);
            },
            $config['param_mappers']
        );
        $collectorDefinition = $container->findDefinition('eps.req2cmd.collector.param_collector');
        $collectorDefinition->replaceArgument(0, $mappers);
    }
}
