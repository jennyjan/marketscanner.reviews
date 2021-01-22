<?php

use Bitrix\Main\Entity;

class MarketScannerReviewElementsTable extends Entity\DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }
    
    public static function getTableName()
    {
        return 'market_scanner_reviews_elements';
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
            'AUTHOR' => array(
                'data_type' => 'string'
            ),
            'DATE' => array(
                'data_type' => 'datetime'
            ),
            'COMMENT' => array(
                'data_type' => 'text'
            ),
            'POSITIVE' => array(
                'data_type' => 'text'
            ),
            'NEGATIVE' => array(
                'data_type' => 'text'
            ),
            'SCORE' => array(
                'data_type' => 'integer'
            ),
            'MARKET_SCANER_REVIEW_ID' => array(
                'data_type' => 'integer'
            ),
        );
    }
}
