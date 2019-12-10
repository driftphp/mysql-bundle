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

namespace Drift\Mysql\Tests;

use Drift\Mysql\DependencyInjection\CompilerPass\MysqlUrlBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class MysqlUrlBuilderTest.
 */
class MysqlUrlBuilderTest extends TestCase
{
    /**
     * Test build url.
     *
     * @dataProvider dataUrlBuilder
     */
    public function testUrlBuilder(
        string $url,
        array $configuration
    ) {
        $this->assertEquals($url,
            MysqlUrlBuilder::buildUrlByConfiguration($configuration)
        );
    }

    /**
     * Data for test build url.
     *
     * @return array
     */
    public function dataUrlBuilder(): array
    {
        return [
            ['usr:pss@127.0.0.1:3306/orders', ['host' => '127.0.0.1', 'user' => 'usr', 'password' => 'pss', 'database' => 'orders']],
            ['usr:pss@127.0.0.1:8888/orders', ['host' => '127.0.0.1', 'user' => 'usr', 'password' => 'pss', 'database' => 'orders', 'port' => 8888]],
        ];
    }
}
