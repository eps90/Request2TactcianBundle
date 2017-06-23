<?php
declare(strict_types=1);

namespace Eps\Req2CmdBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Req2CmdConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     * @throws \RuntimeException
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder();

        $root = $builder->root('req2cmd');
        $root
            ->children()
                ->append($this->addExtractorNode())
                ->append($this->addParamMappersNode())
            ->end();

        return $builder;
    }

    private function addExtractorNode(): NodeDefinition
    {
        $builder = new TreeBuilder();
        $root = $builder->root('extractor');
        $root
            ->addDefaultsIfNotSet()
            ->beforeNormalization()
                ->ifString()
                ->then(function ($extractorName) {
                    return ['service_id' => 'eps.req2cmd.extractor.' . $extractorName];
                })
            ->end()
            ->children()
                ->scalarNode('service_id')
                    ->cannotBeEmpty()
                    ->defaultValue('eps.req2cmd.extractor.serializer')
                ->end()
            ->end();

        return $root;
    }

    private function addParamMappersNode(): NodeDefinition
    {
        $builder = new TreeBuilder();
        $node = $builder->root('param_mappers');
        $node
            ->defaultValue(['eps.req2cmd.param_mapper.path'])
            ->prototype('scalar')
                ->cannotBeEmpty()
            ->end();

        return $node;
    }
}
