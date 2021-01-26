<?php
namespace frontend\controllers;

use yii\httpclient\client;
use yii\web\Controller;
use frontend\models\Parser;

/**
 * Класс для парсинга страничек HTML
 * 
 * @author SadDinamo
 */
class ParserController extends Controller
{
    /**
     * Var_dump отчет с многомерным массивом тикеров маржинальной торговли
     */
    public function actionMarginsharesarray () {
        $MarginSharesArray = Parser::GetMarginSharesArray();
        return $this->render('marginshares', ['a' => $MarginSharesArray]);
    }

    /**
     * Var_dump отчет с информацией по тикеру с yahoo.finance
     * 
     */
    public function actionYahootickerinfo ($ticker = 'FIZZ') {
        $a = Parser::yahooTickerInfo($ticker);
        return $this->render('yahootickerinfo', ['a' => $a]);
    }
}