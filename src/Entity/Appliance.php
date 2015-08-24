<?php

/*
 * This file is part of the NGCSv1 library.
 *
 * (c) Tim Garrity <timgarrity89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NGCSv1\Entity;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class Appliance extends AbstractEntity
{
	public $id;
	public $name;
	public $os_family;
	public $os;
	public $os_version;
	public $architecure;
	public $os_image_type;
	public $type;
	public $min_hdd_size;
	public $licenses;
	public $automatic_installation = true;
    /**
     * @param \stdClass|array $parameters
     */
    public function build($parameters)
    {
        foreach ($parameters as $property => $value) {
            switch ($property) {
                default:
                    $this->{\NGCSv1\convert_to_camel_case($property)} = $value;
                    break;
            }
        }
    }
}
