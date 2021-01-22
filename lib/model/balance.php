<?php
/**
 * @see https://market-scanner.ru/doc/balance
 */
namespace MarketScanner\Reviews\Model;

use MarketScanner\Reviews\Model\Http\Request;
use Exception;

class Balance extends Base {

    /**
     * @var int 32
     */
    private $balance;

    /**
     * Balance constructor.
     *
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->setEntityUrl('/balance');

        parent::__construct([
            'key' => $key,
        ]);
    }

    /**
     * Get last balance
     *
     * @return int
     */
    public function getBalance() : int
    {
        return $this->balance ?? 0;
    }

    /**
     * @param int $balance
     *
     * @return Balance
     */
    public function setBalance($balance) : self
    {
        $this->balance = (int) $balance;

        return $this;
    }
}