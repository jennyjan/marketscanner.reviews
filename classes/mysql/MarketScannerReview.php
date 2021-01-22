<?php

use Bitrix\Main\Entity;

class MarketScannerReviewTable extends Entity\DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }
    
    public static function getTableName()
    {
        return 'market_scanner_reviews_done';
    }
    
    public static function getMap()
    {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autoincrement' => true
            ),
            'ELEMENT_ID' => array(
                'data_type' => 'integer'
            ),
            'REVIEW_COUNT' => array(
                'data_type' => 'integer'
            ),
            'IS_DONE' => array(
                'data_type' => 'string'
            ),
        );
    }
}
