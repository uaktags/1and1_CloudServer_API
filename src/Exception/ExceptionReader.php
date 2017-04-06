<?php

/*
 * This file is part of the NGCSv1 library.
 *
 * (c) Tim Garrity <timgarrity89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NGCSv1\Exception;

/**
 * @author liverbool <nukboon@gmail.com>
 */
class ExceptionReader
{
    /**
     * @var string Error id
     */
    protected $id;

    /**
     * @var string Error message
     */
    protected $message;

    /**
     * @var int Exception error code
     */
    protected $code;

    /**
     * Error message in DigitalOcean format.
     *
     * @param string $content
     * @param int    $code    (optional)
     */
    public function __construct($content, $code = 0)
    {
        $codeId  = empty($code) ? null : $code;
        $message = empty($content) ? 'Request not processed.' : $content;

        // just example to modify message
        $message = str_replace(
            array('server', 'server'),
            array('Machine', 'machine'),
            $message
        );

        $this->id      = $codeId;
        $this->code    = $code;
        $this->message = $message;
    }

    /**
     * Message Id (DigitalOcean error code).
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Error message.
     *
     * @param bool $includeCodeId (optional)
     *
     * @return string
     */
    public function getMessage($includeCodeId = true)
    {
        if ($includeCodeId) {
            $message = $this->message;
            if ($this->code) {
                $message = sprintf('[%d] %s', $this->code, $message);
            }

            return $message;
        }

        return $this->message;
    }
}
