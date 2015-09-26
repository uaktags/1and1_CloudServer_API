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

use NGCSv1\Entity\PrivateNetwork as PrivateNetworkEntity;
use NGCSv1\Entity\Server as ServerEntity;
use NGCSv1\Entity\Hardware as HardwareEntity;


/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class Server extends AbstractApi
{
    /**
     * @param bool|false $detail //Warning this could create a lot of requests.
     * @return array
     */
    public function getAll($detail = false)
    {
        $servers = $this->adapter->get(sprintf('%s/servers', self::ENDPOINT));

        if($detail)
        {
            $int = 0;
            foreach($servers as $k)
            {
                $server = $this->getById($k->id);
                foreach($server as $sk=>$sv)
                {
                    if(!isset($servers[$int]->$sk))
                        $servers[$int]->$sk = $sv;
                }
                $int++;
            }
        }
        return array_map(function ($server) {
            return new serverEntity($server);
        }, $servers);
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
        $server = $this->adapter->get(sprintf('%s/servers/%s', self::ENDPOINT, $id));
        return new serverEntity($server);
    }

    /**
     * @param string     $name
     * @param string     $region
     * @param string     $size
     * @param string|int $image
     * @param bool       $backups           (optional)
     * @param bool       $ipv6              (optional)
     * @param bool       $privateNetworking (optional)
     * @param int[]      $sshKeys           (optional)
     * @param string     $userData          (optional)
     *
     * @throws \RuntimeException
     *
     * @return serverEntity
     */
    public function create($name ='New Server', $hardware, $appliance, $description='', $password ='', $power=true, $firewall=0, $ip=0, $loadbalance=0, $monitor=0)
    {
        $headers = array('Content-Type: application/json');

        $data = array(
            'name'=>$name,
            'hardware'=>$hardware,
            'appliance_id'=>$appliance,
            'password'=>$password,
            'description'=>$description,
            'power_on'=>$power
        );

        if($firewall !=0)
            $data['firewall_policy_id']=$firewall;

        if($ip!=0)
            $data['ip_id']=$ip;

        if($loadbalance!=0)
            $data['load_balancer_id'] = $loadbalance;

        if($monitor!=0)
            $data['monitoring_policy_id']=$monitor;

        $content = json_encode($data);
        $server = $this->adapter->post(sprintf('%s/servers', self::ENDPOINT),  $content);
        die(var_dump($server));
        $server = json_decode($server);

        return new serverEntity($server->server);
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     */
    public function delete($id)
    {
        $this->adapter->delete(sprintf('%s/servers/%s', self::ENDPOINT, $id));
    }

    /**
     * @param $id
     * @param $name
     */
    public function renameServer($id, $name)
    {
        $content = array(
            'name' => $name
        );
        $this->adapter->put(sprintf('%s/servers/%s?server_id={$id}', self::ENDPOINT, $id), $content);
    }

    /**
     * @param $id
     * @param $description
     */
    public function setDescription($id, $description)
    {
        $content = array(
            'description' => $description
        );
        $this->adapter->put(sprintf('%s/servers/%s?server_id={$id}', self::ENDPOINT, $id), $content);
    }

    /**
     * @param $id
     * @return HardwareEntity
     */
    public function getHardware($id)
    {
        $server = $this->adapter->get(sprintf('%s/servers/%s/hardware', self::ENDPOINT, $id));
        return new HardwareEntity($server);
    }

    /**
     * @param $id
     * @return ServerEntity
     */
    public function getStatus($id)
    {
        $server = $this->adapter->get(sprintf('%s/servers/%s/status', self::ENDPOINT, $id));
        return new serverEntity($server);
    }

    /**
     * @param $id
     * @return ServerEntity
     */
    public function getDVD($id)
    {
        $server = $this->adapter->get(sprintf('%s/servers/%s/dvd', self::ENDPOINT, $id));
        return new serverEntity($server);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function unloadDVD($id)
    {
        return $this->adapter->delete(sprintf('%s/servers/%s/dvd', self::ENDPOINT, $id));
    }

    /**
     * @param $id
     * @param $dvdid
     * @return string
     */
    public function loadDVD($id, $dvdid)
    {
        return $this->adapter->put(sprintf('%s/servers/%s/dvd?server_id={$id}', self::ENDPOINT, $id), array('id' => $dvdid));
    }

    /**
     * @param $id
     * @return ServerEntity
     */
    public function getNetworks($id)
    {
        $server = $this->adapter->get(sprintf('%s/servers/%s/private_networks', self::ENDPOINT, $id));
        return new serverEntity($server);
    }

    /**
     * @param $id
     * @param $networkID
     * @return ServerEntity
     */
    public function addNetworkToServer($id, $networkID)
    {
        $server = $this->adapter->post(sprintf('%s/servers/%s/private_networks', self::ENDPOINT, $id), array('id' => $networkID));
        return new serverEntity($server);
    }

    /**
     * @param $id
     * @return ServerEntity
     */
    public function getSnapshots($id)
    {
        $server = $this->adapter->get(sprintf('%s/servers/%s/snapshots', self::ENDPOINT, $id));
        return new serverEntity($server);
    }

    /**
     * @param $id
     * @param $networkID
     * @return PrivateNetworkEntity
     */
    public function getNetworkByID($id, $networkID)
    {
        $server = $this->adapter->get(sprintf('%s/servers/%s/private_networks/%s', self::ENDPOINT, $id, $networkID));
        return new PrivateNetworkEntity($server);
    }

    /**
     * @param $id
     * @return string
     */
    public function cloneServer($id)
    {
        $content = array(
            'server_id' => $id
        );
        return $this->adapter->post(sprintf('%s/servers/%s/clone', self::ENDPOINT, $id), $content);
    }

    public function getFixedInstances()
    {
        $instances = $this->adapter->get(sprintf('%s/servers/fixed_instance_sizes', self::ENDPOINT));

        return array_map(function ($instance) {
            return new HardwareEntity($instance);
        }, $instances);
    }
}
