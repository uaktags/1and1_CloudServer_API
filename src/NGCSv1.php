<?php

/*
 * This file is part of the NGCSv1 library.
 *
 * (c) Tim Garrity <timgarrity89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NGCSv1;

use NGCSv1\Adapter\AdapterInterface;
use NGCSv1\Api\PublicIP;
use NGCSv1\Api\Image;
use NGCSv1\Api\Server;
use NGCSv1\Api\MonitorCenter;
use NGCSv1\Api\MonitorPolicy;
use NGCSv1\Api\PrivateNetwork;
use NGCSv1\Api\Log;
use NGCSv1\Api\User;
use NGCSv1\Api\Usage;
use NGCSv1\Api\Appliance;
use NGCSv1\Api\FirewallPolicy;
use NGCSv1\Api\DVD;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class NGCSv1
{
    /**
     * @see http://semver.org/
     */
    const VERSION = '0.1.2-dev';

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function publicip()
	{
		return new PublicIP($this->adapter);
	}
	
	public function Server()
	{
		return new Server($this->adapter);
	}
	public function privatenetwork()
	{
		return new PublicNetwork($this->adapter);
	}
	public function firewallpolicy()
	{
		return new FirewallPolicy($this->adapter);
	}
	public function monitorpolicy()
	{
		return new MonitorPolicy($this->adapter);
	}
	public function monitorcenter()
	{
		return new MonitorCenter($this->adapter);
	}
	public function dvd()
	{
		return new DVD($this->adapter);
	}
	
}
