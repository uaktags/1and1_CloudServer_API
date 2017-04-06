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

use NGCSv1\Entity\Roles as RoleEntity;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class Roles extends AbstractApi
{
    /**
     * @return RoleEntity[]
     */
    public function getAll()
    {
        $roles = $this->adapter->get(sprintf('%s/roles', self::ENDPOINT));

        return array_map(function ($user) {
            return new RoleEntity($user);
        }, $roles);
    }

    /**
     * @param int $id
     * @throws \RuntimeException
     * @return RoleEntity
     */
    public function getById($id)
    {
        $user = $this->adapter->get(sprintf('%s/roles/%s', self::ENDPOINT, $id));
        return new RoleEntity($user);
    }
}
