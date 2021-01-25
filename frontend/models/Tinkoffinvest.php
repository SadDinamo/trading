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

use common\models\TksPreferencies;
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

    private function ClientRegister()
    {
        switch (TksPreferencies::findByPreferenceName('ClientType')->Value) {
            case 'Sandbox':
                SELF::$client->sbRegister();
                break;
            case 'Real':
                break;
        }        
    }

    public function ClientUnregister()
    {
        switch (TksPreferencies::findByPreferenceName('ClientType')->Value) {
            case 'Sandbox':
                SELF::$client->sbRemove();
                break;
            case 'Real':
                break;
        } 
    }

    /**
     * Залогиниться в клинете песочницы
     */
    public function startClient()
    {
        switch (TksPreferencies::findByPreferenceName('ClientType')->Value) {
            case 'Sandbox':
                if (SELF::$client) { } else {
                    SELF::$client = new TIClient(TkspreferenciesController::getToken(), TISiteEnum::SANDBOX);
                    SELF::ClientRegister();
                }
            break;
            case 'Real':
                if (SELF::$client) { } else {
                    SELF::$client = new TIClient(TkspreferenciesController::getToken(), TISiteEnum::EXCHANGE);
                    SELF::ClientRegister();
                }
            break;
        };
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
    private static function sandboxAddRub($amount)
    {
        SELF::$client->sbCurrencyBalance($amount, TICurrencyEnum::RUB);
    }

    /**
     * Только для SANDBOX
     * Добавляет $Amount - количество $Ticker тикера на счет
     */
    private static function sandboxPutStocksToAccount($amount, $ticker)
    {
        SELF::$client->sbPositionBalance($amount, $ticker);
    }

    /**
     * Только для SANDBOX
     * Удаляет все позииции со счета
     */
    private static function sandboxClearAllPositions()
    {
        SELF::$client->sbClear();
    }

    /**
     * Получение массива акций
     */
    public function getTksStocks()
    {
        $stocks = SELF::$client->getStocks();
        return $stocks;
    }

    /**
     * Получение массива облигаций
     */
    public function getTksBonds(){
        $bonds = SELF::$client->getBonds();
        return $bonds;
    }

    /**
     * Получение массива ETF
     */
    public function getTksEtfs() {
        $etfs = SELF::$client->getEtfs();
        return $etfs;
    }

    /**
     * Получение массива валютообменных курсов
     */
    public function getTksCurrencies() {
        $currencies = SELF::$client->getCurrencies();
        return $currencies;
    }

    /**
     * 
     * 
     */
    public function getHistoryOrderBook ($figi,$depth) {
        $book = SELF::$client->getHistoryOrderBook($figi,$depth);
        return $book;
    }

    /**
     * 
     * 
     */
    public function getTksHistoricalCandles (String $figi = 'BBG000N9MNX3') {
        $from = new \DateTime();
        $from->sub(new \DateInterval("P7D"));
        $to = new \DateTime();
        $candles = SELF::$client->getHistoryCandles($figi, $from, $to, TIIntervalEnum::MIN15);
        return $candles;
    }

    /**
     * Возвращает масив объектов счетов
     * 
     * @return Array [ Object jamesRUS52\TinkoffInvest\TIAccount ]
     */
    public function getTksAccounts () {
        $accounts = SELF::$client->getAccounts();
        return $accounts;
    }

    /**
     * Возвращает объект с данными текущего портфеля
     * 
     * @return Object jamesRUS52\TinkoffInvest\TIPortfolio
     */
    public function getTksPortfolio () {
        $portfolio = SELF::$client->getPortfolio();
        return $portfolio;
    }

    /**
     * Возвращает баланс счета в заданной валюте-параметре
     * 
     * @return integer/float
     */
    public function getTksCurrencyBalance(String $TICurrencyEnum) {
        $portfolio = SELF::getTksPortfolio();
        $CurrencyBalance = $portfolio->getCurrencyBalance(TICurrencyEnum::getCurrency($TICurrencyEnum));
        return $CurrencyBalance;
    }



}