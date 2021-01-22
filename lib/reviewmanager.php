<?
namespace MarketScanner\Reviews;

use MarketScannerReviewTable;
use MarketScannerReviewElementsTable;
use MarketScanner\Reviews\Scanner;
use Bitrix\Main\Diag\Debug;
use \Datetime;

class ReviewManager
{
    private $scanner;
    private $date;
    private $logsPath;
    
    public function __construct($key, $logsPath = '/local/modules/marketscanner.reviews/logs') {
        $this->scanner = new Scanner($key);
        $this->date = new DateTime();
        $this->logsPath = $logsPath;
    }

    public function getItemReviews($elementId, $modelId)
    {
        $reviewsQuantity = $this->getReviewCountByModelId($modelId);
        $reviewEntry = $this->getReviewEntry($elementId);
        if ($reviewsQuantity == $reviewEntry["REVIEW_COUNT"]) {
            MarketScannerReviewTable::update($reviewEntry['ID'], ['IS_DONE' => 'Y']);
            $logText = 'Для товара c id = '.$elementId.' нет новых отзывов.';
            $this->writeLog($logText);
        } elseif ($reviewEntry["REVIEW_COUNT"] < $reviewsQuantity) {
            $reviews = $this->scanner->getReviews($modelId);
            $this->saveReviews($elementId, $modelId, $reviews, $reviewEntry, $reviewsQuantity);
        }
    }

    public function getDate($date = '', $format = 'Y-m-d')
    {
        $date = new \DateTime($date);
        return new \Bitrix\Main\Type\DateTime($date->format($format), $format);
    }

    //8 июня 2013, Волгоград | 26 июля | сегодня -> 8.06.2013 | 26.06.ТЕКУЩИЙ ГОД | текущая дата
    public function prepareDateStr($dateStr, $todayDate)
    {
        $dateStr = explode(',', $dateStr);
        $dateArr = explode(' ', $dateStr[0]);
        $monthsList = array("января" => "01", "февраля" => "02", 
            "марта" => "03", "апреля" => "04", "мая" => "05", "июня" => "06", 
            "июля" => "07", "августа" => "08", "сентября" => "09",
            "октября" => "10", "ноября" => "11", "декабря" => "12");
        if ($dateArr[1] = $monthsList[$dateArr[1]]) {
            $dateArr[2] = $dateArr[2] ?? $todayDate->format('Y');
            return implode(".", $dateArr);
        } else {
            return $todayDate->format('d.m.Y');
        }
    }

    public function getReviewEntry($elementId)
    {
        $reviewEntry = [];
        $reviewEntry = MarketScannerReviewTable::query()
            ->addSelect('ID')
            ->addSelect('REVIEW_COUNT')
            ->where('ELEMENT_ID', $elementId)
            ->exec()
            ->fetch();
        if (!$reviewEntry) {
            $arFields = array(
                'ELEMENT_ID' => $elementId,
                'REVIEW_COUNT' => 0,
            );
            $addEntry = MarketScannerReviewTable::add($arFields);
            $reviewEntry = ['ID' => $addEntry->getId(), 'REVIEW_COUNT' => 0];
        }
        return $reviewEntry;
    }

    public function writeLog($logText)
    {
        Debug::writeToFile($logText, "", sprintf('%s/MarketScannerReviewLog-%s.log', $this->logsPath, $this->date->format('d.m.Y')));
    }

    public function getReviewCountByModelId($modelId)
    {
        $modelInfo = $this->scanner->getInfo($modelId);
        return $modelInfo->getReviewsQuantity();
    }

    public function saveReviews($elementId, $modelId, $reviews, $reviewEntry, $reviewsQuantity)
    {
        foreach ($reviews as $review) {
            $reviewId = $review->uid;
            $savedReview = MarketScannerReviewElementsTable::query()
                ->addSelect('ID')
                ->where('ELEMENT_ID', $elementId)
                ->where('MARKET_SCANER_REVIEW_ID', $reviewId)
                ->exec()->fetchRaw();

            if (!$savedReview) {
                $review->postDate = $this->prepareDateStr($review->postdate, $this->date);
                $fields = array(
                    'ELEMENT_ID' => $elementId,
                    'AUTHOR' => strip_tags(trim($review->author)),
                    'DATE' => $this->getDate($review->postDate),
                    'COMMENT' => strip_tags(trim($review->comment)),
                    'POSITIVE' => strip_tags(trim($review->pluses)),
                    'NEGATIVE' => strip_tags(trim($review->minuses)),
                    'SCORE' => $review->rating,
                    'MARKET_SCANER_REVIEW_ID' => $reviewId
                );
                $result = MarketScannerReviewElementsTable::add($fields);
                if ($result->isSuccess()) {                    
                    $reviewBaseId = $result->getId();
                    $logText = 'Новый отзыв id = '.$reviewBaseId.' добавлен (MARKET_SCANER_REVIEW_ID = '.$reviewId.', id товара = '.$elementId.').';
                    $this->writeLog($logText);
                }
            } else {
                $logText = 'Для товара c id = '.$elementId.' уже есть отзыв c MARKET_SCANER_REVIEW_ID = '.$reviewId;
                $this->writeLog($logText);
            }
        }
        MarketScannerReviewTable::update($reviewEntry['ID'], array('IS_DONE' => "Y", "REVIEW_COUNT" => $reviewsQuantity));
        return true;
    }
}