<?php

/*
 * This file is part of the Drift Http Kernel
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

/**
 * Class MysqlUrlBuilder.
 */
class MysqlUrlBuilder
{
    /**
     * Build an url by a configuration.
     *
     * @param array $configuration
     *
     * @return string
     */
    public static function buildUrlByConfiguration(array $configuration): string
    {
        return rtrim(sprintf(
            '%s:%s@%s:%s/%s',
            rawurlencode($configuration['user']),
            rawurlencode($configuration['password']),
            $configuration['host'],
            $configuration['port'] ?? '3306',
            $configuration['database']
        ), '/');
    }
}
