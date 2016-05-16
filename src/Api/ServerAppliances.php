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

use NGCSv1\Entity\Appliance as ApplianceEntity;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class ServerAppliances extends AbstractApi
{
    /**
     * @return ApplianceEntity[]
     */
    public function getAll()
    {
        $applicances = $this->adapter->get(sprintf('%s/server_appliances', self::ENDPOINT));

        if($this->contenttype == 'json')
        {
            return $applicances;
        }

        return array_map(function ($server) {
            return new ApplianceEntity($server);
        }, json_decode($applicances));
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
        $applicances = $this->adapter->get(sprintf('%s/server_appliances/%s', self::ENDPOINT, $id));

        if($this->contenttype == 'json')
        {
            return $applicances;
        }

        return new ApplianceEntity(json_decode($applicances));
    }
}
