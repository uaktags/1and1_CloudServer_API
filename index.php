<?php

require 'vendor/autoload.php';

use NGCSv1\Adapter\HttpAdapter;
use NGCSv1\NGCSv1;

// create an adapter with your user's API Token
// found in your CloudPanel under "Users"
$adapter = new HttpAdapter('');

// create a ngcs object with the previous adapter
$ngcs = new NGCSv1($adapter);

// initialize the Server Entity
$server = $ngcs->server();
// Get All Servers in your account
$servers = $server->getAll();
// Specify a particular server by it's ID
$aserver = $server->getById("9954B9CB401E0A8361AF73E8563FCE5F");
// Get All Public IPs
$allIPs = $ngcs->PublicIP()->getAll();
// Get all Monitoring Policies
$allMonitors = $ngcs->MonitorCenter()->getAll();
// Get All Firewall Policies
$allFirewalls = $ngcs->FirewallPolicy()->getAll();
// Get All DVDs available
$allDVDs = $ngcs->DVD()->getAll();

