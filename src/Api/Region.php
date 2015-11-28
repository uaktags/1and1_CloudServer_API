<?php

namespace NGCSv1\Api;

use NGCSv1\Entity\Region as RegionEntity;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class Region extends AbstractApi
{
    /**
     * @return RegionEntity[]
     */
    public function getAll()
    {
        $regions = $this->adapter->get(sprintf('%s/regions', self::ENDPOINT));

        return array_map(function ($region) {
            return new RegionEntity($region);
        }, $regions);
    }

    /**
     * @param int $id
     * @throws \RuntimeException
     * @return RegionEntity
     */
    public function getById($id)
    {
        $region = $this->adapter->get(sprintf('%s/region/%s', self::ENDPOINT, $id));
        return new RegionEntity($region);
    }
}