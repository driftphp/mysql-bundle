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

namespace Drift\Mysql\Tests\Services;

use React\MySQL\ConnectionInterface;
use React\MySQL\Io\LazyConnection;

/**
 * Class AService.
 */
class AService
{
    /**
     * @var ConnectionInterface
     */
    private $connection1;

    /**
     * @var ConnectionInterface
     */
    private $connection2;

    /**
     * @var ConnectionInterface
     */
    private $connection3;

    /**
     * AService constructor.
     *
     * @param ConnectionInterface $connection1
     * @param LazyConnection      $connection2
     * @param LazyConnection      $connection3
     */
    public function __construct(ConnectionInterface $usersConnection, LazyConnection $ordersConnection, LazyConnection $users2Connection)
    {
        $this->connection1 = $usersConnection;
        $this->connection2 = $ordersConnection;
        $this->connection3 = $users2Connection;
    }

    /**
     * Are equal.
     */
    public function areOK()
    {
        return $this->connection1 !== $this->connection2
            && $this->connection1 === $this->connection3;
    }
}
