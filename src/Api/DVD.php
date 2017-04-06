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

use NGCSv1\Entity\DVD as DVDEntity;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class DVD extends AbstractApi
{
    /**
     * @return dvdEntity[]
     */
    public function getAll($opts = array())
    {
        $query = array();
        if(array_key_exists('perpage', $opts))
            array_push($query, 'per_page='.(int) $opts['perpage']);
        if(array_key_exists('page', $opts))
            array_push($query, 'page='.(int) $opts['page']);

        $q = implode('&', $query);

        if(isset($q))
            $q = sprintf('%s/dvd_isos', self::ENDPOINT) . '?'.$q;
        else
            $q = sprintf('%s/dvd_isos', self::ENDPOINT);

        $dvds = $this->adapter->get($q);

        if($this->contenttype == 'json')
        {
            return $dvds;
        }

        return array_map(function ($dvd) {
            return new DVDEntity($dvd);
        }, $dvds);
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return DVDEntity
     */
    public function getById($id)
    {
        $dvd = $this->adapter->get(sprintf('%s/dvd_isos/%s', self::ENDPOINT, $id));
        return new DVDEntity($dvd);
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return DVDEntity
     */
    public function get($id = null, $opts = array())
    {
        $query = array();
        if(array_key_exists('perpage', $opts))
            array_push($query, 'per_page='.(int) $opts['perpage']);
        if(array_key_exists('page', $opts))
            array_push($query, 'page='.(int) $opts['page']);

        $q = implode('&', $query);

        if($id == null)
            $dvd = $this->adapter->get(sprintf('%s/dvd_isos', self::ENDPOINT). (isset($q)?'?' . $q:''));
        else
            $dvd = $this->adapter->get(sprintf('%s/dvd_isos/%s', self::ENDPOINT, $id). (isset($q)?'?' . $q:''));
        return new DVDEntity($dvd);
    }
}
