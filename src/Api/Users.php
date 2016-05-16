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

use NGCSv1\Entity\User as UserEntity;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class Users extends AbstractApi
{
    /**
     * @return userEntity[]
     */
    public function getAll()
    {
        $users = $this->adapter->get(sprintf('%s/Users', self::ENDPOINT));

        if($this->contenttype == 'json')
        {
            return $users;
        }

        return array_map(function ($user) {
            return new UserEntity($user);
        }, json_decode($users));
    }

    /**
     * @param int $id
     * @throws \RuntimeException
     * @return userEntity
     */
    public function getById($id)
    {
        $user = $this->adapter->get(sprintf('%s/Users/%s', self::ENDPOINT, $id));

        if($this->contenttype == 'json')
        {
            return $user;
        }

        return new UserEntity(json_decode($user));
    }

    /**
     * @param $id
     * @return UserEntity
     */
    public function getApiByUserId($id)
    {
        $user = $this->adapter->get(sprintf('%s/Users/%s/api', self::ENDPOINT, $id));

        if($this->contenttype == 'json')
        {
            return $user;
        }

        return new UserEntity(json_decode($user));
    }

    /**
     * @param $id
     * @return UserEntity
     */
    public function getApiKeyByUserId($id)
    {
        $user = $this->adapter->get(sprintf('%s/Users/%s/api/key', self::ENDPOINT, $id));

        if($this->contenttype == 'json')
        {
            return $user;
        }

        return new UserEntity(json_decode($user));
    }

    /**
     * @param $id
     * @return UserEntity
     */
    public function getAllowedIpsByUserId($id)
    {
        $user = $this->adapter->get(sprintf('%s/Users/%s/api/ips', self::ENDPOINT, $id));

        if($this->contenttype == 'json')
        {
            return $user;
        }

        return new UserEntity(json_decode($user));
    }

    /**
     * @param $uid
     * @param $ip
     * @return UserEntity
     */
    public function removeAllowedIpFromUser($uid, $ip)
    {
        $user = $this->adapter->delete(sprintf('%s/users/%s/api/ips/%s', self::ENDPOINT, $uid, $ip));

        if($this->contenttype == 'json')
        {
            return $user;
        }

        return new UserEntity(json_decode($user));
    }

    /**
     * @param $uid
     * @param array $ips
     * @return UserEntity
     */
    public function addAllowedIpToUser($uid, $ips =[])
    {
        $user = $this->adapter->post(sprintf('%s/users/%s/api/ips', self::ENDPOINT, $uid), ['ips'=>$ips]);

        if($this->contenttype == 'json')
        {
            return $user;
        }

        return new UserEntity(json_decode($user));
    }

    /**
     * @param $uid
     * @return UserEntity
     */
    public function changeApiKeyForUser($uid)
    {
        $user = $this->adapter->put(sprintf('%s/users/%s/api/key', self::ENDPOINT, $uid));

        if($this->contenttype == 'json')
        {
            return $user;
        }

        return new UserEntity(json_decode($user));
    }

    /**
     * @param $uid
     * @return UserEntity
     */
    public function toggleApiforUser($uid)
    {
        $user = $this->adapter->put(sprintf('%s/users/%s/api', self::ENDPOINT, $uid));

        if($this->contenttype == 'json')
        {
            return $user;
        }

        return new UserEntity(json_decode($user));
    }

    /**
     * @param $uid
     * @param array $data
     * @param string $desc
     * @param string $email
     * @param string $password
     * @param string $state
     * @return UserEntity
     */
    public function modifyUserData($uid, $data =[], $desc = '', $email = '', $password = '', $state = '')
    {
        if(!empty($data))
        {
            if(!isset($data['description']))
            {
                if($desc != '')
                    $data['description'] = $desc;
            }
            if(!isset($data['email']))
            {
                if($email != '')
                    $data['email'] = $email;
            }
            if(!isset($data['password']))
            {
                if($password != '')
                    $data['password'] = $password;
            }
            if(!isset($data['state']))
            {
                if($state != '')
                    $data['state'] = $state;
            }
        }else{
            $data['description'] = $desc;
            $data['email'] = $email;
            $data['password'] = $password;
            $data['state'] = $state;
        }

        $user = $this->adapter->put(sprintf('%s/users/%s', self::ENDPOINT, $uid), $data);

        if($this->contenttype == 'json')
        {
            return $user;
        }

        return new UserEntity(json_decode($user));
    }

    /**
     * @param $uid
     * @return UserEntity
     */
    public function deleteUserById($uid)
    {
        $user = $this->adapter->delete(sprintf('%s/users/%s', self::ENDPOINT, $uid));

        if($this->contenttype == 'json')
        {
            return $user;
        }

        return new UserEntity(json_decode($user));
    }
}
