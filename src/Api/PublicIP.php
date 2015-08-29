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

use NGCSv1\Entity\PublicIP as PublicIPEntity;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class PublicIP extends AbstractApi
{
    /**
     * @return serverEntity[]
     */
    public function getAll()
    {
        $ips = $this->adapter->get(sprintf('%s/public_ips', self::ENDPOINT));

        return array_map(function ($ip) {
            return new PublicIPEntity($ip);
        }, $ips);
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
        $ip = $this->adapter->get(sprintf('%s/public_ips/%s', self::ENDPOINT, $id));
        return new serverEntity($ip);
    }


}
