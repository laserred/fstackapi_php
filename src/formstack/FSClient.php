<?php

/**
 * Created by PhpStorm.
 * User: smatt
 * Date: 22/06/2017
 * Time: 01:32 PM
 */
namespace FormStack;


use FSExceptions\FSException;
use Config\ConfigHelper;
use GuzzleHttp\Client;

class FSClient
{

    protected $token;
    protected $baseUrl;
    protected $client;
    protected $xmlResponseType;

    /**
     * FSClient constructor.
     * @param null $token
     * @param null $baseUrl
     * @param bool $xmlResponseType
     */
    protected function __construct($token = null, $baseUrl = null, $xmlResponseType = false) {

        if(is_null($token)) {
            $token = ConfigHelper::config()["access_token"];
        }
        if(is_null($baseUrl))  {
            $baseUrl = ConfigHelper::config()["base_url"];
        }

        $this->token = $token;
        $this->baseUrl = $baseUrl;
        $this->xmlResponseType = $xmlResponseType;

        $this->verifyTokenIsSet();

        $headerOptions = [
            "Authorization" => "Bearer ".$this->token,
        ];

        $this->client = new Client([
            "base_uri" => $this->baseUrl,
            "headers" => $headerOptions
        ]);

    }


    public function log() {
        return "baseUri = $this->baseUrl \ntoken = $this->token";
    }


    private function verifyTokenIsSet() {
        if(is_null($this->token) || is_null($this->baseUrl) || !preg_match("/.*\/{1}$/", $this->baseUrl))
            throw new FSException(
              "Formstack Access Token/Base Url Not Set in Config file [formstack.php]. Ensure the baseUrl ends with a forward slash '/'"
            );
    }

    /**
     * @return mixed|null
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * @param mixed|null $token
     */
    public function setToken($token) {
        $this->token = $token;
    }

    /**
     * @return mixed|null
     */
    public function getBaseUrl() {
        return $this->baseUrl;
    }

    /**
     * @param mixed|null $baseUrl
     */
    public function setBaseUrl($baseUrl) {
        $this->baseUrl = $baseUrl;
    }

    public function validateId($formId) {
        if(is_null($formId) || strlen($formId) <= 0) {
            throw new FSException("The supplied form id should not be null and empty");
        }
    }
}