<?php

/*
 * This file is part of the NGCSv1 library.
 *
 * (c) Tim Garrity <timgarrity89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NGCSv1\Adapter;

use NGCSv1\Exception\ExceptionInterface;
use NGCSv1\Exception\ResponseException;

class CurlResponse
{

    var $headers = array();

    function CurlResponse () {}

    function parse_header ($handle, $header)
    {
        $details = explode(":", $header, 2);//split(':', $header, 2);

        if (count($details) == 2)
        {
            $key   = trim($details[0]);
            $value = trim($details[1]);

            $this->headers[$key] = $value;
        }

        return strlen($header);
    }

}
/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class CurlAdapter extends AbstractAdapter implements AdapterInterface
{
    /**
     * @var Browser
     */
    protected $api;

    /**
     * @var ExceptionInterface
     */
    protected $exception;

    /**
     * @param string             $accessToken
     * @param Browser            $browser     (optional)
     * @param ListenerInterface  $listener    (optional)
     * @param ExceptionInterface $exception   (optional)
     */
    public function __construct($accessToken, Browser $browser = null, ListenerInterface $listener = null, ExceptionInterface $exception = null)
    {
        $this->api = $accessToken;
        $this->exception = $exception;
    }

    /**
     * {@inheritdoc}
     */
    public function get($url)
    {
        $request = curl_init();
        $response = new CurlResponse();
        curl_setopt($request, CURLOPT_URL, $url);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_HEADERFUNCTION, array(&$response, 'parse_header'));
        curl_setopt($request, CURLOPT_HTTPHEADER, array("X-TOKEN:". $this->api, "Content-type:application/json"));
        curl_setopt($request, CURLOPT_CUSTOMREQUEST, "GET");

        //Try to get the response
        $body = curl_exec($request);
        if ($body == false){
            return( curl_error($request));
        }
        else{
            $this->maxRequestLimit = $response->headers['X-Rate-Limit-Limit'];
            $this->requestLimit = $response->headers['X-Rate-Limit-Remaining'];
            return($body);
        }

        curl_close($request);

    }

    /**
     * {@inheritdoc}
     */
    public function delete($url, $content = '')
    {

    }

    /**
     * {@inheritdoc}
     */
    public function put($url, $content = '')
    {

    }

    /**
     * {@inheritdoc}
     */
    public function post($url, $content = '')
    {

    }

    public function isResponseOk($code)
    {
        switch($code) {
            case '200':
                return true;
            case '201':
                return true;
            case '202':
                return true;
            case '400':
                throw new ResponseException('Bad Request Made', '400');
            case '401':
                throw new ResponseException('Unauthorized API Token', '401');
            case '403':
                throw new ResponseException('Forbidden Command', '403');
            case '404':
                throw new ResponseException('Command Not Found', '404');
            case '405':
                throw new ResponseException('Method Now Allowed', '405');
            case '406':
                throw new ResponseException('IP Not Allowed Access', '406');
            case '429':
                throw new ResponseException('Too Many Requests Per Hour', '429');
            case '500':
                throw new ResponseException('Internal Server Error. Contact Admin', '500');
            default:
                throw new ResponseException('Unknown Error');
        }
    }

    /**
     * @param Response $response
     *
     * @return \Exception
     */
    protected function handleResponse(Response $response)
    {
        if ($this->exception) {
            return $this->exception->create($response->getContent(), $response->getStatusCode());
        }

        $content = json_decode($response->getContent());

        return new \RuntimeException(
            sprintf('[%d] %s (%s)', $response->getStatusCode(), $content->message, $content->id)
        );
    }
}
