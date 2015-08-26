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
use NGCSv1\Entity\Image as ImageEntity;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class Image extends AbstractApi
{
    /**
     * @param array $criteria
     *
     * @return ImageEntity[]
     */
    public function getAll(array $criteria = [])
    {
        $query = sprintf('%s/images?per_page=%d', self::ENDPOINT, PHP_INT_MAX);

        if (isset($criteria['type']) && in_array($criteria['type'], ['distribution', 'application'])) {
            $query = sprintf('%s&type=%s', $query, $criteria['type']);
        }

        if (isset($criteria['private']) && true === (boolean) $criteria['private']) {
            $query = sprintf('%s&private=true', $query);
        }

        $images = $this->adapter->get($query);
        $images = json_decode($images);
        $this->extractMeta($images);

        return array_map(function ($image) {
            return new ImageEntity($image);
        }, $images->images);
    }

    /**
     * @param int $id
     *
     * @return ImageEntity
     */
    public function getById($id)
    {
        $image = $this->adapter->get(sprintf('%s/images/%d', self::ENDPOINT, $id));
        $image = json_decode($image);

        return new ImageEntity($image->image);
    }
}
