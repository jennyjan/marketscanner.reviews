<?php

namespace MarketScanner\Reviews;

use MarketScanner\Reviews\Model\Balance;
use MarketScanner\Reviews\Model\Info;
use MarketScanner\Reviews\Model\Photos;
use MarketScanner\Reviews\Model\Specs;
use MarketScanner\Reviews\Model\Reviews;

class Scanner {

    private $key;

    /**
     * Scanner constructor.
     *
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * @return int
     */
    public function getBalance() : int
    {
        return (new Balance($this->key))->getBalance();
    }

    /**
     * @param int $id
     *
     * @return Info
     */
    public function getInfo(int $id) : Info
    {
        return new Info($this->key, $id);
    }

    /**
     * @param int $id
     * @param string $size
     *
     * @return array
     */
    public function getPhotos(int $id, string $size = '') : array
    {
        $photos = new Photos($this->key, $id);

        return $photos->getPictures($size);
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function getSpecs(int $id) : array
    {
        $specs = new Specs($this->key, $id);

        return $specs->getSpecifications();
    }

    public function getReviews(int $id, int $quantity = 10, int $min = 0) : array
    {
        $reviews = new Reviews($this->key, $id, $quantity, $min);

        return $reviews->getReviews();
    }
}