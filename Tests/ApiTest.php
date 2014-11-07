<?php

/**
 * @author    Markus Tacker <m@cto.hiv>
 */

namespace Coderbyheart\MailChimpBundle\MailChimp\Tests;

use Buzz\Message\Request;
use Buzz\Message\Response;
use Coderbyheart\MailChimpBundle\MailChimp\Api;

class ApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @group MailChimpBundle
     */
    public function itShouldBeInstantiable()
    {
        $this->assertInstanceOf('\Coderbyheart\MailChimpBundle\MailChimp\Api', $this->createTestObject());
    }

    /**
     * @test
     * @group                    MailChimpBundle
     * @depends                  itShouldBeInstantiable
     * @expectedException        \Coderbyheart\MailChimpBundle\Exception\BadMethodCallException
     * @expectedExceptionMessage Api key "invalidkey" has invalid format.
     */
    public function itShouldFailOnInvalidApiKey()
    {
        new Api('invalidkey');
    }

    /**
     * @test
     * @group   MailChimpBundle
     * @depends itShouldBeInstantiable
     */
    public function itShouldCallTheAPI()
    {
        $mockClient = $this->getMock('\Buzz\Client\ClientInterface');
        $mockClient->expects($this->once())->method('send')
            ->with(
                $this->callback(function (Request $request) {
                    $this->assertEquals('POST', $request->getMethod());
                    $this->assertEquals(
                        'https://def.api.mailchimp.com/2.0/lists/batch-unsubscribe.json',
                        $request->getHost() . $request->getResource()
                    );
                    $data = json_decode($request->getContent());
                    $this->assertEquals('abc-def', $data->apikey);
                    $this->assertEquals(array('email' => 'john.doe@example.com'), (array)$data->batch[0]);
                    return true;
                }),
                $this->callback(function (Response $response) {
                    $response->setContent(json_encode(array(
                        'success_count' => 1,
                        'error_count'   => 0,
                    )));
                    return true;
                })
            );

        $api = $this->createTestObject();
        $api->setClient($mockClient);
        $result = $api->listsBatch_unsubscribe(array('batch' => array(array('email' => 'john.doe@example.com'))));
        $this->assertEquals(1, $result->success_count);
        $this->assertEquals(0, $result->error_count);
    }

    /**
     * @test
     * @group                    MailChimpBundle
     * @depends                  itShouldBeInstantiable
     * @dataProvider             failRequestReturnTypeProvider
     * @expectedException        \Coderbyheart\MailChimpBundle\Exception\BadMethodCallException
     * @expectedExceptionMessage Request to "some/endpoint" failed: The message.
     */
    public function itShouldFailOnApiError($returnType)
    {
        $mockClient = $this->getMock('\Buzz\Client\ClientInterface');
        $mockClient->expects($this->once())->method('send')
            ->with(
                $this->isInstanceOf('Buzz\Message\Request'),
                $this->callback(function (Response $response) {
                    $response->setContent(json_encode(array(
                        "status" => "error",
                        "code"   => -99,
                        "name"   => "Unknown_Exception",
                        "error"  => "The message."
                    )));
                    return true;
                })
            );

        $api = $this->createTestObject();
        $api->setReturnType($returnType);
        $api->setClient($mockClient);
        $api->someEndpoint(array('batch' => array(array('email' => 'john.doe@example.com'))));
    }

    /**
     * @test
     * @group                    MailChimpBundle
     * @depends                  itShouldBeInstantiable
     * @expectedException        \Coderbyheart\MailChimpBundle\Exception\BadMethodCallException
     * @expectedExceptionMessage Invalid endpoint name: invalid_endpoint
     */
    public function itShouldFailOnInvalidEndpointName()
    {
        $api = $this->createTestObject();
        $api->invalid_endpoint();
    }

    /**
     * @test
     * @group                    MailChimpBundle
     * @depends                  itShouldBeInstantiable
     * @expectedException        \Coderbyheart\MailChimpBundle\Exception\BadMethodCallException
     * @expectedExceptionMessage Invalid return type "invalid" given.
     */
    public function itShouldFailOnInvalidReturnType()
    {
        $api = new Api('123-us1');
        $api->setReturnType('invalid');
    }

    /**
     * @test
     * @group   MailChimpBundle
     * @depends itShouldBeInstantiable
     */
    public function itShouldReturnAsArray()
    {
        $mockClient = $this->getMock('\Buzz\Client\ClientInterface');
        $mockClient->expects($this->once())->method('send')
            ->with(
                $this->callback(function (Request $request) {
                    $this->assertEquals('POST', $request->getMethod());
                    $this->assertEquals(
                        'https://def.api.mailchimp.com/2.0/lists/batch-unsubscribe.json',
                        $request->getHost() . $request->getResource()
                    );
                    $data = json_decode($request->getContent());
                    $this->assertEquals('abc-def', $data->apikey);
                    $this->assertEquals(array('email' => 'john.doe@example.com'), (array)$data->batch[0]);
                    return true;
                }),
                $this->callback(function (Response $response) {
                    $response->setContent(json_encode(array(
                        'success_count' => 1,
                        'error_count'   => 0,
                    )));
                    return true;
                })
            );

        $api = $this->createTestObject();
        $api->setReturnType('array');
        $api->setClient($mockClient);
        $result = $api->listsBatch_unsubscribe(array('batch' => array(array('email' => 'john.doe@example.com'))));

        $this->assertInternalType('array', $result);
        $this->assertSame(1, $result['success_count']);
        $this->assertSame(0, $result['error_count']);
    }

    protected function createTestObject()
    {
        return new Api('abc-def');
    }

    public function failRequestReturnTypeProvider()
    {
        return array(array('object'), array('array'));
    }
}
