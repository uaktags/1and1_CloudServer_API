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

use NGCSv1\Entity\FirewallPolicy;
use NGCSv1\Entity\Harddrive;
use NGCSv1\Entity\Image;
use NGCSv1\Entity\LoadBalancer;
use NGCSv1\Entity\PrivateNetwork as PrivateNetworkEntity;
use NGCSv1\Entity\PublicIP;
use NGCSv1\Entity\Server as ServerEntity;
use NGCSv1\Entity\Hardware as HardwareEntity;
use NGCSv1\Entity\Snapshots;


/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class Server extends AbstractApi
{
    /**
     * @param bool|false $detail //Warning this could create a lot of requests.
     * @return array
     */
    public function getAll($detail = false, $opts = array())
    {
        $query = array();
        if(array_key_exists('perpage', $opts))
            array_push($query, 'per_page='.(int) $opts['perpage']);
        if(array_key_exists('page', $opts))
            array_push($query, 'page='.(int) $opts['page']);

        $q = implode('&', $query);

        if(isset($q))
            $q = sprintf('%s/servers', self::ENDPOINT) . '?'.$q;
        else
            $q = sprintf('%s/servers', self::ENDPOINT);

        $servers = $this->adapter->get($q);

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
        if($this->contenttype == 'json')
        {
            return $servers;
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
        if($this->contenttype == 'json')
        {
            return $server;
        }
        return new serverEntity(json_decode($server));
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
    public function create($name ='New Server', $opts = array())
    {
        //$hardware, $appliance, $description='', $password ='', $power=true, $firewall=0, $ip=0, $loadbalance=0, $monitor=0
        $headers = array('Content-Type: application/json');

        $data = array(
            'name'=>$opts['name'],
            'hardware'=>$opts['hardware'],
            'appliance_id'=>$opts['appliance'],
            'password'=>$opts['password'],
            'description'=>$opts['description'],
            'power_on'=>$opts['power']
        );

        if($opts['firewall'] !=0)
            $data['firewall_policy_id']=$opts['firewall'];

        if($opts['ip']!=0)
            $data['ip_id']=$opts['ip'];

        if($opts['loadbalance']!=0)
            $data['load_balancer_id'] = $opts['loadbalance'];

        if($opts['monitor']!=0)
            $data['monitoring_policy_id']=$opts['monitor'];

        $content = json_encode($data);
        $server = $this->adapter->post(sprintf('%s/servers', self::ENDPOINT),  $content);

        if($this->contenttype == 'json')
        {
            return $server;
        }

        return new serverEntity(json_decode($server)->server);
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


    public function modifyServer($id, $name, $desc)
    {

    }
    /**
     * @param $id
     * @return HardwareEntity
     */
    public function getHardware($id)
    {
        $server = $this->adapter->get(sprintf('%s/servers/%s/hardware', self::ENDPOINT, $id));

        if($this->contenttype == 'json')
        {
            return $server;
        }

        return new HardwareEntity(json_decode($server));
    }

    /**
     * @param $id
     * @return ServerEntity
     */
    public function getStatus($id)
    {
        $server = $this->adapter->get(sprintf('%s/servers/%s/status', self::ENDPOINT, $id));

        if($this->contenttype == 'json')
        {
            return $server;
        }

        return new serverEntity(json_decode($server));
    }

    /**
     * @param $id
     * @return ServerEntity
     */
    public function getDVD($id)
    {
        $server = $this->adapter->get(sprintf('%s/servers/%s/dvd', self::ENDPOINT, $id));

        if($this->contenttype == 'json')
        {
            return $server;
        }

        return new serverEntity(json_decode($server));
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

        if($this->contenttype == 'json')
        {
            return $server;
        }

        return new serverEntity(json_decode($server));
    }

    /**
     * @param $id
     * @param $networkID
     * @return ServerEntity
     */
    public function addNetworkToServer($id, $networkID)
    {
        $server = $this->adapter->post(sprintf('%s/servers/%s/private_networks', self::ENDPOINT, $id), array('id' => $networkID));

        if($this->contenttype == 'json')
        {
            return $server;
        }

        return new serverEntity(json_decode($server));
    }

    /**
     * @param $id
     * @return ServerEntity
     */
    public function getSnapshots($id)
    {
        $server = $this->adapter->get(sprintf('%s/servers/%s/snapshots', self::ENDPOINT, $id));

        if($this->contenttype == 'json')
        {
            return $server;
        }

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

        if($this->contenttype == 'json')
        {
            return $server;
        }

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

    /**
     * @return array
     *
     */
    public function getFixedInstances()
    {
        $instances = $this->adapter->get(sprintf('%s/servers/fixed_instance_sizes', self::ENDPOINT));

        if($this->contenttype == 'json')
        {
            return $instances;
        }

        return array_map(function ($instance) {
            return new HardwareEntity($instance);
        }, json_decode($instances));
    }

    /**
     *
     *
     */
    public function getHarddrives($id)
    {
        $hdds = $this->adapter->get(sprintf('%s/servers/%s/hardware/hdds/%s', self::ENDPOINT, $id));

        if($this->contenttype == 'json')
        {
            return $hdds;
        }

        return array_map(function ($hdd) {
            return new Harddrive($hdd);
        }, json_decode($hdds));
    }

    /**
     *
     *
     */
    public function getHarddrive($id, $hdd)
    {
        $hdds = $this->adapter->get(sprintf('%s/servers/%s/hardware/hdds/%s', self::ENDPOINT, $id, $hdd));

        if($this->contenttype == 'json')
        {
            return $hdds;
        }

        return new Harddrive(json_decode($hdds));
    }

    /**
     *
     *
     */
    public function getServerImage($id)
    {
        $image = $this->adapter->get(sprintf('%s/servers/image', self::ENDPOINT));

        if($this->contenttype == 'json')
        {
            return $image;
        }

        return new Image(json_decode($image));
    }

    /**
     *
     *
     */
    public function getServerIPs($id)
    {
        $ips = $this->adapter->get(sprintf('%s/servers/%s/ips', self::ENDPOINT, $id));

        if($this->contenttype == 'json')
        {
            return $ips;
        }

        return array_map(function ($ip) {
            return new PublicIP($ip);
        }, json_decode($ips));
    }

    /**
     *
     *
     */
    public function getServerIP($id, $ip)
    {
        $ips = $this->adapter->get(sprintf('%s/servers/%s/ips/%s', self::ENDPOINT, $id, $ip));

        if($this->contenttype == 'json')
        {
            return $ips;
        }

        return array_map(function ($ipid) {
            return new PublicIP($ipid);
        }, json_decode($ips));
    }

    /**
     *
     *
     */
    public function getFirewallForIP($id, $ip)
    {
        $firewalls = $this->adapter->get(sprintf('%s/servers/%s/ips/%s/firewall_policy', self::ENDPOINT, $id, $ip));

        if($this->contenttype == 'json')
        {
            return $firewalls;
        }

        return array_map(function ($firewall) {
            return new FirewallPolicy($firewall);
        }, json_decode($firewalls));
    }

    /**
     *
     *
     */
    public function getLoadBalancerForIP($id, $ip)
    {
        $balancers = $this->adapter->get(sprintf('%s/servers/%s/ips/%s/load_balancers', self::ENDPOINT, $id, $ip));

        if($this->contenttype == 'json')
        {
            return $balancers;
        }

        return array_map(function ($balancer) {
            return new LoadBalancer($balancer);
        }, json_decode($balancers));
    }

    /**
     *
     *
     */
    public function getServerSnapshots($id)
    {
        $snaps = $this->adapter->get(sprintf('%s/servers/%s/snapshots', self::ENDPOINT, $id));

        if($this->contenttype == 'json')
        {
            return $snaps;
        }

        return array_map(function ($snap) {
            return new Snapshots($snap);
        }, json_decode($snaps));
    }

    /**
     *
     *
     */
    public function deleteHarddrive($id, $hdd)
    {
        $this->adapter->delete(sprintf('%s/servers/%s/hardware/hdds/%s', self::ENDPOINT, $id, $hdd));

        return $this->getByID(json_decode($id));
    }

    /**
     *
     *
     */
    public function deleteIPfromServer($id, $ip, $keep = false)
    {
        $this->adapter->delete(sprintf('%s/servers/%s/ips/%s', self::ENDPOINT, $id, $ip), array('keep' => $keep));

        return $this->getByID(json_decode($id));
    }

    /**
     *
     *
     */
    public function deleteLoadBalancer($id, $ip, $load)
    {
        $this->adapter->delete(sprintf('%s/servers/%s/ips/%s/load_balancers/%s', self::ENDPOINT, $id, $ip, $load));

        return $this->getById(json_decode($id));
    }

    /**
     *
     *
     */
    public function removeServerFromNetwork($id, $priv)
    {
        $this->adapter->delete(sprintf('%s/servers/%s/private_networks/%s', self::ENDPOINT, $id, $priv));

        return $this->getById(json_decode($id));
    }

    /**
     *
     *
     */
    public function deleteSnapshot($id, $snap)
    {
        $this->adapter->delete(sprintf('%s/servers/', self::ENDPOINT));

        return $this->getById(json_decode($id));
    }

    /**
     *
     *
     */
    public function addNewHarddrive($id, $size, $main = false)
    {
        $a = $this->adapter->get(sprintf('%s/servers/', self::ENDPOINT));
    }

    /**
     *
     *
     */
    public function addNewIP($id, $v4 = true)
    {
        $a = $this->adapter->get(sprintf('%s/servers/', self::ENDPOINT));
    }

    /**
     *
     *
     */
    public function createSnapshot($id)
    {
        $a = $this->adapter->get(sprintf('%s/servers/', self::ENDPOINT));
    }

    /**
     *
     *
     */
    public function modifyHardware($id, $fixed = '', $vcore = '', $coreper = '', $ram = '')
    {
        $a = $this->adapter->get(sprintf('%s/servers/', self::ENDPOINT));
    }

    /**
     *
     *
     */
    public function modifyHarddrive($id, $hdd, $size)
    {
        $a = $this->adapter->get(sprintf('%s/servers/', self::ENDPOINT));
    }

    /**
     *
     *
     */
    public function reinstallImage($id, $image, $password = '', $firewall)
    {

    }

    /**
     *
     *
     */
    public function addFirewallToIP($id, $ip, $firewall)
    {

    }

    /**
     *
     *
     */
    public function powerOffServer($id, $action, $method)
    {
        //$action = POWER_ON, POWER_OFF, REBOOT
        //$method = "SOFTWARE, HARDWARE
    }

    /**
     *
     *
     */
    public function restoreSnapshot($id, $snapshot)
    {

    }
}
