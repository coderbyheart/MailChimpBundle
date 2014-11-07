<?php

/**
 * @author    Markus Tacker <m@cto.hiv>
 */

namespace Coderbyheart\MailChimpBundle\MailChimp;

use Buzz\Client\ClientInterface;
use Coderbyheart\MailChimpBundle\Exception\BadMethodCallException;
use Buzz\Browser;
use Buzz\Client\Curl;
use Buzz\Message\Response;

class Api
{

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $returnType = 'object';

    /**
     * @var string
     */
    private $dataCenter;

    /**
     * @var string
     */
    private $format = 'json';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * Constructor.
     *
     * @param string $apiKey     MailChimp API key.
     *
     * @throws BadMethodCallException
     */
    public function __construct($apiKey)
    {
        if (!preg_match('/^[0-9a-f]+-[0-9a-z]+$/', $apiKey)) {
            throw new BadMethodCallException(sprintf('Api key "%s" has invalid format.', $apiKey));
        }

        list(, $this->dataCenter) = explode('-', $apiKey);

        $this->apiKey = $apiKey;
    }

    protected function post($endpoint, $args)
    {
        $request         = array_merge($args, array(
            'apikey' => $this->apiKey,
        ));
        $requestData     = json_encode($request);
        $client          = $this->getClient();
        $browser         = new Browser($client);
        $response        = $browser->post(
            sprintf('https://%s.api.mailchimp.com/2.0/%s.%s', $this->dataCenter, $endpoint, $this->format),
            array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($requestData),
                'User-Agent: CoderbyheartMailChimpBundle',
                'Accept: application/json'
            ),
            $requestData
        );

        $method = 'parseAs' . ucfirst($this->returnType);
        list($status, $errorMessage, $data) = $this->$method($response->getContent());

        if ('error' === $status) {
            throw new BadMethodCallException(sprintf('Request to "%s" failed: %s', $endpoint, $errorMessage));
        }

        return $data;
    }

    /**
     * Parses the content as array.
     *
     * @param string $content
     *
     * @return array
     */
    protected function parseAsArray($content)
    {
        $data = json_decode($content, true);

        return array(
            isset($data['status']) ? $data['status'] : false,
            isset($data['error']) ? $data['error'] : false,
            $data,
        );
    }

    /**
     * Parses the content as objects.
     *
     * @param string $content
     *
     * @return array
     */
    protected function parseAsObject($content)
    {
        $data = json_decode($content);

        return array(
            property_exists($data, 'status') ? $data->status : false,
            property_exists($data, 'error') ? $data->error : false,
            $data
        );
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
            throw new BadMethodCallException(sprintf('Invalid endpoint name: %s', $method));
        }

        $result = $this->post(
            strtolower($matches[1] . '/' . str_replace('_', '-', $matches[2])),
            empty($args) ? array() : $args[0]
        );

        return $result;
    }

    /**
     * @return ClientInterface
     */
    protected function getClient()
    {
        return $this->client === null ? new Curl() : $this->client;
    }

    /**
     * Use to override the internal default curl buzz client used for calling the API.
     *
     * @param ClientInterface $client
     *
     * @return self
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Sets the return type of responses.
     *
     * @param string $returnType
     *
     * @return self
     * @throws \Coderbyheart\MailChimpBundle\Exception\BadMethodCallException
     */
    public function setReturnType($returnType)
    {
        if (!in_array($returnType, array('object', 'array'))) {
            throw new BadMethodCallException(sprintf('Invalid return type "%s" given.', $returnType));
        }

        $this->returnType = $returnType;

        return $this;
    }
}
