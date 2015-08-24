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

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class Action extends AbstractApi
{
    /**
     * @return ActionEntity[]
     */
    public function getAll()
    {
        $actions = $this->adapter->get(sprintf('%s/actions?per_page=%d', self::ENDPOINT, PHP_INT_MAX));
        $actions = json_decode($actions);

        $this->extractMeta($actions);

        return array_map(function ($action) {
            return new ActionEntity($action);
        }, $actions->actions);
    }

    /**
     * @param int $id
     *
     * @return ActionEntity
     */
    public function getById($id)
    {
        $action = $this->adapter->get(sprintf('%s/actions/%d', self::ENDPOINT, $id));
        $action = json_decode($action);

        $this->meta = null;

        return new ActionEntity($action->action);
    }
}
