<?php

namespace Tests\Functional;

use Oacc\Authentication\Jwt;

class AuthTest extends BaseTestCase
{
    public function testPostRegisterWithValidData()
    {
        $data = [
            "username" => "TestName_".uniqid(),
            "email" => "testemail@".uniqid().".com",
            "password" => "aaaaaaaa",
            "password_confirm" => "aaaaaaaa",
        ];
        $response = $this->client->request('post', 'register', ['json' => $data]);
        $data = $this->getData($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('success', $data->status);
        $this->assertObjectHasAttribute('messages', $data);
    }

    public function testPostRegisterWithNoData()
    {
        $data = [];
        $response = $this->client->request('post', 'register', ['json' => $data]);
        $data = $this->getData($response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('error', $data->status);
        $this->assertObjectHasAttribute('errors', $data);
    }

    public function testPostRegisterWithInvalidData()
    {
        $data = [
            "username" => "TestName",
            "email" => "testemail@.com",
            "password" => "aaaaaaaa",
            "password_confirm" => "aaaaaaaa",
        ];
        $response = $this->client->request('post', 'register', ['json' => $data]);
        $data = $this->getData($response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('error', $data->status);
        $this->assertObjectHasAttribute('errors', $data);
        $this->assertObjectHasAttribute('username', $data->errors);
        $this->assertObjectHasAttribute('email', $data->errors);
    }

    public function testPostLoginWithValidData()
    {
        $data = [
            "username" => "TestName",
            "password" => "aaaaaaaa",
        ];
        $response = $this->client->request('post', 'login', ['json' => $data]);
        $data = $this->getData($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('success', $data->status);
        $this->assertObjectHasAttribute('messages', $data);
    }

    public function testGetAdminWithValidData()
    {
        $token = Jwt::create('TestName', ['ROLE_USER']);
        $headers = ['Authorization' => "Bearer ".$token];
        $response = $this->client->request('get', 'admin', ['headers' => $headers]);
        $data = $this->getData($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('success', $data->status);
        $this->assertObjectHasAttribute('data', $data);
        $this->assertObjectHasAttribute('user', $data->data);
    }

}
