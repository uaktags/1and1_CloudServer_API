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

use NGCSv1\Entity\FirewallPolicy as FirewallEntity;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class FirewallPolicy extends AbstractApi
{
    /*
     * GET Functions
     */
    /**
     * @return FirewallEntity[]
     */
    public function getAll($opts=array())
    {
        $query = array();
        if(array_key_exists('perpage', $opts))
            array_push($query, 'per_page='.(int) $opts['perpage']);
        if(array_key_exists('page', $opts))
            array_push($query, 'page='.(int) $opts['page']);

        $q = implode('&', $query);

        if(isset($q))
            $q = sprintf('%s/firewall_policies', self::ENDPOINT) . '?'.$q;
        else
            $q = sprintf('%s/firewall_policies', self::ENDPOINT);

        $firewalls = $this->adapter->get($q);

        if($this->contenttype == 'json')
        {
            return $firewalls;
        }

        return array_map(function ($firewall) {
            return new FirewallEntity($firewall);
        }, json_decode($firewalls));
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return FirewallEntity
     */
    public function getById($id)
    {
        $firewall = $this->adapter->get(sprintf('%s/firewall_policies/%s', self::ENDPOINT, $id));
        return new FirewallEntity($firewall);
    }

    public function getServerIPs($id)
    {
        return $this->adapter->get(sprintf('%s/firewall_policies/%s/server_ips', self::ENDPOINT, $id));
    }

    public function getServerIPByIP($id, $ip)
    {
        return $this->adapter->get(sprintf('%s/firewall_policies/%s/server_ips/%s', self::ENDPOINT, $id, $ip));
    }

    public function getRulesByPolicyID($id)
    {
        return $this->adapter->get(sprintf('%s/firewall_policies/%s/rules', self::ENDPOINT, $id));
    }

    public function getFirePolicyRule($id, $ruleID)
    {
        return $this->adapter->get(sprintf('%s/firewall_policies/%s/rules/%s', self::ENDPOINT, $id, $ruleID));
    }

    /**
     * End GET Functions
     */
    /**
     * Delete Functions
     */

    public function deleteFirewallPolicy($id)
    {
        return $this->adapter->delete(sprintf('%s/firewall_policies/%s', self::ENDPOINT, $id));
    }

    public function removeIPfromFirewall($id, $ip)
    {
        return $this->adapter->delete(sprintf('%s/firewall_policies/%s/server_ips/%s', self::ENDPOINT, $id, $ip));
    }

    public function removeRuleFromFirewall($id, $ruleID)
    {
        return $this->adapter->delete(sprintf('%s/firewall_policies/%s/rules/%s', self::ENDPOINT, $id, $ruleID));
    }

    /**
     * End Delete Function
     */
    /**
     * PUT Functions
     */

    public function modify($id, $name=false, $desc=false)
    {
        $body=[];
        if($name!==false)
            $body['name']=$name;
        if($desc!==false)
            $body['description']=$desc;
        return $this->adapter->put(sprintf('%s/firewall_policies/%s', self::ENDPOINT, $id), $body);
    }
}
