<?php

namespace MarketScanner\Reviews\Model\Http;

use Exception;
use stdClass;

class Request {

    const URL = 'https://market-scanner.ru/api';

    /**
     * Final query URL
     *
     * @var string
     */
    private $url;

    /**
     * Post params key => value
     *
     * @var array
     */
    private $data = [];

    /**
     * Make POST request
     *
     * @return stdClass decoded json
     */
    public function exec()
    {
        try {
            $ch = curl_init($this->getUrl());
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
            $data = curl_exec($ch);
            curl_close($ch);

            return (new Response($data))->as_object();
        }
        catch (Exception $e) {
            // die($e->getMessage());
        }
    }

    /**
     * @param array $data
     *
     * @return Request
     */
    public function setData(array $data) : self
    {
        $this->data += $data;

        return $this;
    }

    /**
     * @param string $url
     *
     * @return Request
     */
    public function setUrl(string $url) : self
    {
        $this->url = self::URL . $url;

        return $this;
    }

    /**
     * @return string
     */
    private function getUrl() : string
    {
        return $this->url ?? '';
    }

    /**
     * @return array
     */
    private function getData() : array
    {
        $data = [
            'form_params' => $this->data
        ];

        return $data;
    }
}