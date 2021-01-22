<?php

namespace MarketScanner\Reviews\Model\Http;

use Exception;
use stdClass;

class Response {

    /**
     * @var string
     */
    private $_response;

    /**
     * @var stdClass
     */
    private $_object;

    /**
     * Response constructor.
     *
     * @param string $response
     *
     * @throws Exception
     */
    public function __construct($response)
    {
        try {
            $this->_object = json_decode($response);
            $this->_response = $response;
        }
        catch (Exception $e) {
            // die($e->getMessage());
        }

        if (isset($this->_object->error)) {
            // die($this->_object->error);
        }
    }

    /**
     * @return stdClass
     */
    public function as_object() : stdClass
    {
        return $this->_object ?? new stdClass();
    }

    /**
     * @return string
     */
    public function as_string() : string
    {
        return $this->_response ?? '';
    }
}