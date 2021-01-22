<?
$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/www";
define('STOP_STATISTICS', true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

set_time_limit(0);
ini_set('memory_limit', '-1');

CModule::IncludeModule('marketscanner.reviews');
CModule::IncludeModule("iblock");

$date = new \DateTime();
$review = new MarketScanner\Reviews\ReviewManager('1234567890');

$arSort = array("SORT" => "ASC");
$arSelect = array("ID", "NAME", "PROPERTY_184");
$arFilter = array(
    "IBLOCK_ID" => 2,
    "ACTIVE" => "Y",
    "INCLUDE_SUBSECTIONS" => "Y",
    "!PROPERTY_184_VALUE" => false,
);
$rsItem = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
while($arItem = $rsItem->GetNext()) {
    printf("%s (%s) %s<br />", $arItem['NAME'], $arItem['ID'], $arItem["PROPERTY_184_VALUE"]);
    $reviews = $review->getItemReviews($arItem['ID'], $arItem["PROPERTY_184_VALUE"]);
}
Bitrix\Main\Config\Option::set("marketscanner.reviews", "last_succes_reviews_upload", $date->getTimestamp());
echo "Done!";