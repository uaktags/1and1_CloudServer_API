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

use NGCSv1\Entity\PrivateNetwork as pNetworkEntity;


/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class PrivateNetwork extends AbstractApi
{
    /**
     * @return pNetworkEntity[]
     */
    public function getAll()
    {
        $privatenetworks = $this->adapter->get(sprintf('%s/private_networks', self::ENDPOINT));

        if($this->contenttype == 'json')
        {
            return $privatenetworks;
        }

        return array_map(function ($privatenetwork) {
            return new pNetworkEntity($privatenetwork);
        }, json_decode($privatenetworks));
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return pNetworkEntity
     */
    public function getById($id)
    {
        $privatenetwork = $this->adapter->get(sprintf('%s/private_networks/%s', self::ENDPOINT, $id));

        if($this->contenttype == 'json')
        {
            return $privatenetwork;
        }

        return new pNetworkEntity(json_decode($privatenetwork));
    }

    /**
     * @param $id
     * @return server
     *
     * @desc "Returns a list of the servers attached to a private network."
     */
    public function getServersByNetworkId($id)
    {
        $servers = $this->adapter->get(sprintf('%s/private_networks/%s/servers', self::ENDPOINT, $id));

        if($this->contenttype == 'json')
        {
            return $servers;
        }

        return new server(json_decode($servers));
    }

    /**
     * @param $sid
     * @param $netid
     * @return server
     *
     * @desc "Returns information about a server attached to a private network."
     */
    public function getServerByIdByNetworkId($sid, $netid)
    {
        $server = $this->adapter->get(sprintf('%s/private_networks/%s/servers/%s', self::ENDPOINT, $netid, $sid));

        if($this->contenttype == 'json')
        {
            return $server;
        }

        return new server(json_decode($server));
    }

    public function create($name, $desc = '')
    {
        $data = [
            'name'=> $name,
            'description' => $desc
        ];

        return $this->adapter->post(sprintf('%s/private_networks', self::ENDPOINT), $data);
    }

    public function delete($id)
    {
        return $this->adapter->delete(sprintf('%s/private_networks/%s', self::ENDPOINT, $id));
    }

    public function deleteServerFromNetwork($sid, $netid)
    {
        return $this->adapter->delete(sprintf('%s/private_networks/%s/servers/%s', self::ENDPOINT, $netid, $sid));
    }

    public function modify($id, $name=false, $desc=false, $network=false,$subnet=false)
    {
        $body=[];
        if($name!==false)
            $body['name']=$name;
        if($desc!==false)
            $body['description']=$desc;
        if($network!==false)
            $body['network_address']=$network;
        if($subnet!==false)
            $body['subnet_mask']=$subnet;

        return $this->adapter->put(sprintf('%s/private_networks/%s', self::ENDPOINT, $id), $body);
    }

    public function addServerToNetwork($id, $sid)
    {
        return $this->adapter->post(sprintf('%s/private_networks/%s/servers', self::ENDPOINT, $id), $sid);
    }

}
