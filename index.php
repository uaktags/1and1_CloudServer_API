<?php

require 'vendor/autoload.php';

use NGCSv1\Adapter\HttpAdapter;
use NGCSv1\NGCSv1;

// create an adapter with your access token which can be
// generated at https://cloud.digitalocean.com/settings/applications
$adapter = new HttpAdapter('');

// create a digital ocean object with the previous adapter
$digitalocean = new NGCSv1($adapter);

$server = $digitalocean->server();

// return a collection of server entity
$servers = $server->getAll();
echo "this is all the servers<br>";
var_dump($servers);
echo "<br> <br><br>";
echo"this is 1 server <br>";
var_dump($server->getById("9954B9CB401E0A8361AF73E8563FCE5F"));
echo "<br> <br><br>";
var_dump($digitalocean->PublicIP()->getAll());
echo "<br> <br><br>";
var_dump($digitalocean->MonitorCenter()->getAll());
echo "<br> <br><br>";
var_dump($digitalocean->FirewallPolicy()->getAll());
echo "<br> <br><br>";
var_dump($digitalocean->DVD()->getAll());

