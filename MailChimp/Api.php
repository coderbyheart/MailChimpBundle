<?php

/**
 * @author    Markus Tacker <m@coderbyheart.de>
 * @copyright 2013 Markus Tacker | coder::by(♥); // Freelance Señor Web Backend Dev | http://coderbyheart.de/
 */

namespace Coderbyheart\MailChimpBundle\MailChimp;

use Coderbyheart\MailChimpBundle\Exception\BadMethodCallException;
use Buzz\Browser;
use Buzz\Client\Curl;

class Api
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $dataCenter;

    /**
     * @var string
     */
    private $format = 'json';

    public function __construct($apiKey)
    {
        if (!preg_match('/^[0-9a-f]+-[0-9a-z]+$/', $apiKey)) {
            throw new BadMethodCallException(sprintf('Api key "%s" has invalid format.', $apiKey));
        }
        list($a, $this->dataCenter) = explode('-', $apiKey);
        $this->apiKey = $apiKey;
    }

    protected function post($endpoint, $args)
    {
        $request     = array_merge($args, array(
            'apikey' => $this->apiKey,
        ));
        $requestData = json_encode($request);
        $client      = new Curl();
        $client->setTimeout(30000);
        $browser         = new Browser($client);
        $response        = $browser->post(
            sprintf('https://%s.api.mailchimp.com/2.0/%s.%s', $this->dataCenter, $endpoint, $this->format),
            array(
                //'Content-Type: application/x-www-form-urlencoded',
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($requestData),
                'User-Agent: CoderbyheartMailChimpBundle',
                'Accept: application/json'
            ),
            $requestData
        );
        $responseContent = $response->getContent();
        $responseData    = json_decode($responseContent);
        if (property_exists($responseData, 'status') && $responseData->status == 'error') {
            throw new BadMethodCallException(
                sprintf('Request to "%s" failed: %s', $endpoint, $responseData->error)
            );
        }
        return $responseData;
    }

    /**
     * Magic method with maps instance method calls to API methods.
     *
     * @param $method
     * @param $args
     *
     * @return mixed
     * @throws \Coderbyheart\MailChimpBundle\Exception\BadMethodCallException
     */
    public function __call($method, $args)
    {
        if (!preg_match('/([a-z]+)([A-Z][a-z_]+)$/', $method, $matches)) {
            if (!method_exists($this, $method)) {
                throw new BadMethodCallException(sprintf('Method does not exist: %s:%s', __CLASS__, $method));
            }
            $this->$method($args);
        }
        $result = $this->post(strtolower($matches[1] . '/' . str_replace('_', '-', $matches[2])), empty($args) ? array() : $args[0]);
        return $result;
    }
} 