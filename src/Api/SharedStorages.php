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

use NGCSv1\Entity\SharedStorage as SharedEntity;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class SharedStorages extends AbstractApi
{
    /**
     * @param array $criteria
     *
     * @return SharedEntity[]
     */
    public function getAll()
    {
        $images = $this->adapter->get(sprintf('%s/shared_storages', self::ENDPOINT));

        return array_map(function ($image) {
            return new SharedEntity($image);
        }, $images);
    }

    /**
     * @param int $id
     *
     * @return ImageEntity
     */
    public function getById($id)
    {
        $image = $this->adapter->get(sprintf('%s/shared_storages/%s', self::ENDPOINT, $id));

        return new SharedEntity($image);
    }

    public function create($name, $description = Null, $size= 200)
    {
        $data = [
            'name' => $name,
            'description' => $description,
            'size' => $size
        ];
        return $this->adapter->post(sprintf('%s/shared_storages', self::ENDPOINT), $data);
    }

    public function delete($id)
    {
        return $this->adapter->delete(sprintf('%s/shared_storages/%s', self::ENDPOINT, $id));
    }

    public function modify($id, $name, $description, $size)
    {
        if($name!==false)
            $content['name'] = $name;
        if($description!==false)
            $content['description'] = $description;
        if($size!==false)
            $content['size']=$size;
        return $this->adapter->put(sprintf('%s/shared_storages/%s', self::ENDPOINT, $id), $content);
    }

    public function modifyName($id, $name)
    {
        return $this->modify($id, $name, false, false);
    }

    public function modifyDescription($id, $description)
    {
        return $this->modify($id, false, $description, false);
    }

    public function modifySize($id, $size)
    {
        return $this->modify($id, false, false, $size);
    }

    public function getServers($id)
    {
        return $this->adapter->get(sprintf('%s/shared_storages/%s/servers', self::ENDPOINT, $id));
    }

    public function attachServer($id, $serverID)
    {
        return $this->adapter->post(sprintf('%s/shared_storages/%s/servers?shared_storage_id='.$id, self::ENDPOINT, $id), array('servers'=>array('id'=>$serverID, 'RW')));
    }
}
