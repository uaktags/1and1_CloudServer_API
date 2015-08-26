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

use NGCSv1\Entity\FirewallPolicy as FirewallEntity;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class FirewallPolicy extends AbstractApi
{
    /**
     * @return serverEntity[]
     */
    public function getAll()
    {
        $servers = $this->adapter->get(sprintf('%s/firewall_policies', self::ENDPOINT));

        return array_map(function ($server) {
            return new FirewallEntity($server);
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
        $server = $this->adapter->get(sprintf('%s/firewall_policies/%s', self::ENDPOINT, $id));
        return new FirewallEntity($server['body']);
    }


}
