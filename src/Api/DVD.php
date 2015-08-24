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
     * @return serverEntity[]
     */
    public function getAll()
    {
        $servers = $this->adapter->get(sprintf('%s/dvd_isos', self::ENDPOINT));

        return array_map(function ($server) {
            return new DVDEntity($server);
        }, $servers['body']);
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
        $server = $this->adapter->get(sprintf('%s/dvd_isos/%s', self::ENDPOINT, $id));
        return new DVDEntity($server['body']);
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
    public function create($name, $region, $size, $image, $backups = false, $ipv6 = false,
        $privateNetworking = false, array $sshKeys = array(), $userData = ""
    ) {
        $headers = array('Content-Type: application/json');

        $data = array(
            'name' => $name,
            'region' => $region,
            'size' => $size,
            'image' => $image,
            'backups' => \NGCSv1\bool_to_string($backups),
            'ipv6' => \NGCSv1\bool_to_string($ipv6),
            'private_networking' => \NGCSv1\bool_to_string($privateNetworking)
        );

        if (0 < count($sshKeys)) {
            $data["ssh_keys"] = $sshKeys;
        }

        if (!empty($userData)) {
            $data["user_data"] = $userData;
        }

        $content = json_encode($data);

        $server = $this->adapter->post(sprintf('%s/servers', self::ENDPOINT), $headers, $content);
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
        $headers = array('Content-Type: application/x-www-form-urlencoded');
        $this->adapter->delete(sprintf('%s/servers/%d', self::ENDPOINT, $id), $headers);
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return KernelEntity[]
     */
    public function getAvailableKernels($id)
    {
        $kernels = $this->adapter->get(sprintf('%s/servers/%d/kernels', self::ENDPOINT, $id));
        $kernels = json_decode($kernels);

        $this->meta = $this->extractMeta($kernels);

        return array_map(function ($kernel) {
            return new KernelEntity($kernel);
        }, $kernels->kernels);
    }

    /**
     * @param int $id
     *
     * @return ImageEntity[]
     */
    public function getSnapshots($id)
    {
        $snapshots = $this->adapter->get(sprintf('%s/servers/%d/snapshots?per_page=%d', self::ENDPOINT, $id, PHP_INT_MAX));
        $snapshots = json_decode($snapshots);

        $this->meta = $this->extractMeta($snapshots);

        return array_map(function ($snapshot) {
            $snapshot = new ImageEntity($snapshot);

            return $snapshot;
        }, $snapshots->snapshots);
    }

    /**
     * @param int $id
     *
     * @return ImageEntity[]
     */
    public function getBackups($id)
    {
        $backups = $this->adapter->get(sprintf('%s/servers/%d/backups?per_page=%d', self::ENDPOINT, $id, PHP_INT_MAX));
        $backups = json_decode($backups);

        $this->meta = $this->extractMeta($backups);

        return array_map(function ($backup) {
            return new ImageEntity($backup);
        }, $backups->backups);
    }

    /**
     * @param int $id
     *
     * @return ActionEntity[]
     */
    public function getActions($id)
    {
        $actions = $this->adapter->get(sprintf('%s/servers/%d/actions?per_page=%d', self::ENDPOINT, $id, PHP_INT_MAX));
        $actions = json_decode($actions);

        $this->meta = $this->extractMeta($actions);

        return array_map(function ($action) {
            return new ActionEntity($action);
        }, $actions->actions);
    }

    /**
     * @param int $id
     * @param int $actionId
     *
     * @return ActionEntity
     */
    public function getActionById($id, $actionId)
    {
        $action = $this->adapter->get(sprintf('%s/servers/%d/actions/%d', self::ENDPOINT, $id, $actionId));
        $action = json_decode($action);

        return new ActionEntity($action->action);
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function reboot($id)
    {
        return $this->executeAction($id, array('type' => 'reboot'));
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function powerCycle($id)
    {
        return $this->executeAction($id, array('type' => 'power_cycle'));
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function shutdown($id)
    {
        return $this->executeAction($id, array('type' => 'shutdown'));
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function powerOff($id)
    {
        return $this->executeAction($id, array('type' => 'power_off'));
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function powerOn($id)
    {
        return $this->executeAction($id, array('type' => 'power_on'));
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function passwordReset($id)
    {
        return $this->executeAction($id, array('type' => 'password_reset'));
    }

    /**
     * @param int    $id
     * @param string $size
     * @param bool   $disk  (optional)
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function resize($id, $size, $disk = true)
    {
        return $this->executeAction($id, array('type' => 'resize', 'size' => $size, 'disk' => $disk));
    }

    /**
     * @param int $id
     * @param int $image
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function restore($id, $image)
    {
        return $this->executeAction($id, array('type' => 'restore', 'image' => $image));
    }

    /**
     * @param int        $id
     * @param int|string $image
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function rebuild($id, $image)
    {
        return $this->executeAction($id, array('type' => 'rebuild', 'image' => $image));
    }

    /**
     * @param int    $id
     * @param string $name
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function rename($id, $name)
    {
        return $this->executeAction($id, array('type' => 'rename', 'name' => $name));
    }

    /**
     * @param int $id
     * @param int $kernel
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function changeKernel($id, $kernel)
    {
        return $this->executeAction($id, array('type' => 'change_kernel', 'kernel' => $kernel));
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function enableIpv6($id)
    {
        return $this->executeAction($id, array('type' => 'enable_ipv6'));
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function disableBackups($id)
    {
        return $this->executeAction($id, array('type' => 'disable_backups'));
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function enablePrivateNetworking($id)
    {
        return $this->executeAction($id, array('type' => 'enable_private_networking'));
    }

    /**
     * @param int    $id
     * @param string $name
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function snapshot($id, $name)
    {
        return $this->executeAction($id, array('type' => 'snapshot', 'name' => $name));
    }

    /**
     * @param int   $id
     * @param array $options
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    private function executeAction($id, array $options)
    {
        $headers = array('Content-Type: application/json');
        $content = json_encode($options);

        $action = $this->adapter->post(sprintf('%s/servers/%d/actions', self::ENDPOINT, $id), $headers, $content);
        $action = json_decode($action);

        return new ActionEntity($action->action);
    }
}
