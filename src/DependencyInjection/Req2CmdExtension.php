<?php
declare(strict_types=1);

namespace Eps\Req2CmdBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

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

        $this->configureExtractors($config, $container);
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
}
