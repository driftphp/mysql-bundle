<?php

/*
 * This file is part of the Drift Project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Drift\Mysql\DependencyInjection\CompilerPass;

use React\MySQL\ConnectionInterface;
use React\MySQL\Factory;
use React\MySQL\Io\LazyConnection;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class MysqlCompilerPass.
 */
class MysqlCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container)
    {
        $connectionsConfiguration = $container->getParameter('mysql.connections_configuration');
        if (empty($connectionsConfiguration)) {
            return;
        }

        $this->createFactory($container);
        foreach ($connectionsConfiguration as $connectionName => $connectionConfiguration) {
            $connectionAlias = $this->createConnection($container, $connectionConfiguration);

            $container->setAlias(
                "mysql.{$connectionName}_connection",
                $connectionAlias
            );

            $container->setAlias(
                ConnectionInterface::class,
                $connectionAlias
            );

            $container->setAlias(
                LazyConnection::class,
                $connectionAlias
            );

            $container->registerAliasForArgument($connectionAlias, ConnectionInterface::class, "{$connectionName} connection");
            $container->registerAliasForArgument($connectionAlias, LazyConnection::class, "{$connectionName} connection");
        }
    }

    /**
     * Create factory.
     *
     * @param ContainerBuilder $container
     */
    private function createFactory(ContainerBuilder $container)
    {
        $container->setDefinition('mysql.factory', new Definition(
            Factory::class,
            [
                new Reference('drift.event_loop'),
            ]
        ));
    }

    /**
     * Create connection and return it's reference.
     *
     * @param ContainerBuilder $container
     * @param array            $configuration
     *
     * @return string
     */
    private function createConnection(
        ContainerBuilder $container,
        array $configuration
    ): string {
        $url = MysqlUrlBuilder::buildUrlByConfiguration($configuration);
        $connectionHash = substr(md5($url), 0, 7);
        $definitionName = "mysql.connection.$connectionHash";

        if (!$container->hasDefinition($definitionName)) {
            $definition = new Definition(
                ConnectionInterface::class,
                [
                    $url,
                ]
            );

            $definition->setFactory([
                new Reference('mysql.factory'),
                'createLazyConnection',
            ]);

            $container->setDefinition($definitionName, $definition);
        }

        return $definitionName;
    }
}
