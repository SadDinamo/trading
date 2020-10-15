<?php

namespace frontend\models;
use yii\base\Model;

use \jamesRUS52\TinkoffInvest\TIClient;
use \jamesRUS52\TinkoffInvest\TIAccount;
use \jamesRUS52\TinkoffInvest\TISiteEnum;
use \jamesRUS52\TinkoffInvest\TICurrencyEnum;
use \jamesRUS52\TinkoffInvest\TIInstrument;
use \jamesRUS52\TinkoffInvest\TIPortfolio;
use \jamesRUS52\TinkoffInvest\TIOperationEnum;
use \jamesRUS52\TinkoffInvest\TIIntervalEnum;
use \jamesRUS52\TinkoffInvest\TICandleIntervalEnum;
use \jamesRUS52\TinkoffInvest\TICandle;
use \jamesRUS52\TinkoffInvest\TIOrderBook;
use \jamesRUS52\TinkoffInvest\TIInstrumentInfo;

use frontend\controllers\TkspreferenciesController;

/**
 * 
 * Модель для работы робота с ТКС Инвестициями через API
 * 
 * Источник: https://github.com/jamesRUS52/tinkoff-invest
 *  
 * Получить токены: https://www.tinkoff.ru/invest/settings/
 * 
 */
class Tinkoffinvest extends Model {

    static $client;

    private function sandboxClientRegister()
    {
        SELF::$client->sbRegister();
    }

    public function sandboxClientUnregister()
    {
        SELF::$client->sbRemove();
    }

    public function startSandboxClient()
    {
        if (SELF::$client) { } else {
            SELF::$client = new TIClient(TkspreferenciesController::getSandBoxToken(), TISiteEnum::SANDBOX);
            SELF::$client->sbRegister();
        }
    }

    public function startRealClient()
    {
        if (SELF::$client) { } else {
            SELF::$client = new TIClient(TkspreferenciesController::getToken(), TISiteEnum::EXCHANGE);
            SELF::$client->sbRegister();
        }
    }

    /**
     * Добавить рубли на счет песочницы
     */
    private static function sandboxAddUsd($amount)
    {
        SELF::$client->sbCurrencyBalance($amount, TICurrencyEnum::USD);
    }

    /**
     * Добавить доллары США на счет песочницы
     */
    private static function sandboxAddRub($amount, $client)
    {
        $client->sbCurrencyBalance($amount, TICurrencyEnum::RUB);
    }

    /**
     * Только для SANDBOX
     * Добавляет $Amount - количество $Ticker тикера на счет
     */
    private static function sandboxPutStocksToAccount($amount, $ticker, $client)
    {
        $client->sbPositionBalance($amount, $ticker);
    }

    /**
     * Тольуо для SANDBOX
     * Удаляет все позииции со счета
     */
    private static function sandboxClearAllPositions($client)
    {
        $client->sbClear();
    }

    /**
     * Получение списка акций
     */
    public function getTksStocks()
    {
        $stocks = SELF::$client->getStocks();
        return $stocks;
    }

    /**
     * Получение списка облигаций
     */
    public function getTksBonds(){
        $bonds = SELF::$client->getBonds();
        return $bonds;
    }

    /**
     * Получение списка ETF
     */
    public function getTksEtfs() {
        $etfs = SELF::$client->getEtfs();
        return $etfs;
    }

    /**
     * Получение валютообменных курсов
     */
    public function getTksCurrencies() {
        $currencies = SELF::$client->getCurrencies();
        return $currencies;
    }

    public function getHistoryOrderBook ($figi,$depth) {
        $book = SELF::$client->getHistoryOrderBook($figi,$depth);
        return $book;
    }

    public function getTksHistoricalCandles () {
        $from = new \DateTime();
        $from->sub(new \DateInterval("P7D"));
        $to = new \DateTime();
        $candles = SELF::$client->getHistoryCandles("BBG000BR37X2", $from, $to, TIIntervalEnum::MIN15);
        return $candles;
    }

    public function getTksAccounts () {
        $accounts = SELF::$client->getAccounts();
        return $accounts;
    }

    /**
     * 
     * if $account === null, used default Tinkoff account
     * 
     */
    public function getTksPortfolio () {
        $port = SELF::$client->getPortfolio(TIAccount::$account = null);
        return $port;
    }

    /**
     * 
     * $currency = TICurrencyEnum::RUB
     * 
     */
    public function getTksPortfolioBalance ( $port = getTksPortfolio(), $currency) {
        $portfolioBalance = $port->getCurrencyBalance($currency);
        return $portfolioBalance;
    }

    /**
     * 
     * 
     */
    public function getTksInstrumentLots ($port = getTksPortfolio(), $ticker) {
        $port->getintrumentLots($ticker);
    }

}