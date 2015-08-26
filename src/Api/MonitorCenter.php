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

use NGCSv1\Entity\MonitorCenter as MonitorEntity;


/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class MonitorCenter extends AbstractApi
{
    /**
     * @return serverEntity[]
     */
    public function getAll()
    {
        $monitor = $this->adapter->get(sprintf('%s/monitoring_center', self::ENDPOINT));

        return array_map(function ($server) {
            return new MonitorEntity($server);
        }, $monitor['body']);
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return MonitorEntity
     */
    public function getById($id)
    {
        $server = $this->adapter->get(sprintf('%s/monitoring_center/%s', self::ENDPOINT, $id));
        return new MonitorEntity($server['body']);
    }

    /**
     * @return MonitorEntity
     */
    public function create(  )
    {
        //return new MonitorEntity();
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     */
    public function delete($id)
    {

    }


}
