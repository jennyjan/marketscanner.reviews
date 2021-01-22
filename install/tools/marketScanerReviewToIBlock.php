<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/bitrix/modules/main/include/prolog_before.php';
Bitrix\Main\Loader::includeModule('iblock');
Bitrix\Main\Loader::includeModule('marketscanner.reviews');

$savedReviewIds = [];
$arSelect = ['ID', 'IBLOCK_ID', 'PROPERTY_MARKET_SCANER_REVIEW_ID'];
$arFilter = ['IBLOCK_ID' => 1, '!PROPERTY_MARKET_SCANER_REVIEW_ID' => false];
$dbSavedReviews = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
while ($arSavedReview = $dbSavedReviews->Fetch()) {
    $savedReviewIds[] = $arSavedReview['PROPERTY_MARKET_SCANER_REVIEW_ID_VALUE'];
}
echo "savedReviewIds ";print_r($savedReviewIds);echo "<br>";
$dbTransitionalReviews = MarketScannerReviewElementsTable::query()
    ->addSelect('*')
    ->whereNotIn('MARKET_SCANER_REVIEW_ID', $savedReviewIds)
    ->addOrder('ID', 'DESC')
    ->exec();
while ($arTransitionalReview = $dbTransitionalReviews->fetch()) {   
    $el = new CIBlockElement();
    $fields = array(
        'IBLOCK_ID' => 1,
        'NAME' => 'Отзыв с маркет-сканнер',
        'ACTIVE' => 'Y',
        'DETAIL_TEXT' => $arTransitionalReview['COMMENT'],
        'DATE_CREATE' => $arTransitionalReview['DATE']->format("d.m.Y H:i:s"),
        'IBLOCK_SECTION' => false,
        'PROPERTY_VALUES' => array(
            'author' => $arTransitionalReview['AUTHOR'],
            'score' => (int)$arTransitionalReview['SCORE'],
            'linked_item' => $arTransitionalReview['ELEMENT_ID'],
            'positive' => $arTransitionalReview['POSITIVE'],
            'negative' => $arTransitionalReview['NEGATIVE'],
            'IS_MARKET_REVIEW' => 'Y',
            'MARKET_SCANER_REVIEW_ID' => $arTransitionalReview['MARKET_SCANER_REVIEW_ID'],
        )
    );
    print_r($fields);echo "<br>";
    $id = $el->Add($fields, false, false);
    
    $rs = CIBlockElement::GetList(array(), array('ID'=> (int)$arTransitionalReview['ELEMENT_ID']), false, array('nTopCount'=>1), array('ID', 'PROPERTY__score', 'PROPERTY__score_count'));
    $item = $rs->GetNext();
    if ($item) {
        $new_count = $item['PROPERTY__SCORE_COUNT_VALUE']+1;
        CIBlockElement::SetPropertyValueCode($item['ID'], "_score_count", $new_count);
        if ($fields['PROPERTY_VALUES']['score'] > 0) {
            $new_score = ($item['PROPERTY__SCORE_VALUE']*$item['PROPERTY__SCORE_COUNT_VALUE']+$fields['PROPERTY_VALUES']['score'])/($item['PROPERTY__SCORE_COUNT_VALUE']+1);
            CIBlockElement::SetPropertyValueCode($item['ID'], "_score", $new_score);
        }
    }
}