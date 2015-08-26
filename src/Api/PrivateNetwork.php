<?php

/*
 * This file is part of the NGCSv1 library.
 *
 * (c) Tim Garrity <timgarrity89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NGCSv1\Api;

use NGCSv1\Entity\Action as ActionEntity;
use NGCSv1\Entity\Server as serverEntity;
use NGCSv1\Entity\Image as ImageEntity;
use NGCSv1\Entity\Kernel as KernelEntity;
use NGCSv1\Entity\Upgrade as UpgradeEntity;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class Server extends AbstractApi
{
    /**
     * @return serverEntity[]
     */
    public function getAll()
    {
        $servers = $this->adapter->get(sprintf('%s/private_networks', self::ENDPOINT));

        return array_map(function ($server) {
            return new serverEntity($server);
        }, $servers['body']);
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return serverEntity
     */
    public function getById($id)
    {
        $server = $this->adapter->get(sprintf('%s/private_networks/%s', self::ENDPOINT, $id));
        return new serverEntity($server['body']);
    }


}
