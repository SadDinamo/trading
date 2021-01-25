<?php
namespace frontend\controllers;

use yii\web\Controller;
use yii\data\ArrayDataProvider;
use frontend\models\Tinkoffinvest;

/**
 * Контроллер для модели API части
 */
class TinkoffinvestController extends Controller
{
  public $client;

  /**
   * Get array filtered stocks from market
   * Ex.: $stockes = $client->getStocks(["V","LKOH"]);
   */
  public function GetArrayMarketStocks ($TikersArray) {
      $stocks = $this->client->getStocks($TikersArray);
      return $stocks;
  }

  /**
   * Get array filtered bonds from market
   * Ex.: $instr = $client->getBonds(["RU000A0JX3X7"]);
   */
  public function GetArrayMarketBonds ($TikersArray) {
      $stocks = $this->client->getBonds($TikersArray);
      return $stocks;
  }

  /**
   * Get array filtered ETFs from market
   * Ex.: $instr = $client->getEtfs(["FXRU"]);
   */
  public function GetArrayMarketEtfs ($TikersArray) {
      $stocks = $this->client->getEtfs($TikersArray);
      return $stocks;
  }

  /**
   * Get array filtered currencies from market
   * Ex.: $instr = $client->getCurrencies(["USD000UTSTOM"]);
   */
  public function GetArrayMarketCurrencies ($TikersArray) {
      $stocks = $this->client->getCurrencies($TikersArray);
      return $stocks;
  }

  /**
   * Get instrument by ticker
   */
  public function GetInstumentByTicker ($Ticker) {
      $instr = $this->client->getInstrumentByTicker($Ticker);
      return $instr;
  }

  /**
   * Get instrument by FIGI
   */
  public function GetInstumentByFigi ($Figi) {
      $instr = $this->client->getInstrumentByFigi($Figi);
      return $instr;
  }

  private function GetHistoryOrderBook ($Figi) {
      //$book = $this->client->getHistoryOrderBook("BBG000BR37X2", 1);
      $book = $this->client->getHistoryOrderBook($Figi, 1);
      return $book;
  }

  public function GetHistoricalCandles ($Figi) {
      $from = new \DateTime();
      $from->sub(new \DateInterval("P7D"));
      $to = new \DateTime();
      $candles = $this->client->getHistoryCandles($Figi, $from, $to, TIIntervalEnum::MIN15);
      return $candles;
  }




// *** ЭКШОНЫ ***



  // Получение массива торгуемых акций
  public function actionStocks()
  {
    Tinkoffinvest::StartClient();
    $stocks = Tinkoffinvest::getTksStocks($this->client);
    Tinkoffinvest::ClientUnregister();
    return $this->render('stocks', ['stocks' => $stocks]);
  }

  // Получение массива торгуемых облигаций
  public function actionBonds()
  {
    Tinkoffinvest::StartClient();
    $bonds = Tinkoffinvest::getTksBonds($this->client);
    Tinkoffinvest::ClientUnregister();
    return $this->render('bonds', ['bonds' => $bonds]);
  }

  // Получение массива торгуемых ETF
  public function actionEtfs()
  {
    Tinkoffinvest::StartClient();
    $etfs = Tinkoffinvest::getTksEtfs($this->client);
    Tinkoffinvest::ClientUnregister();
    return $this->render('etfs', ['etfs' => $etfs]);
  }

  // Получение массива торгуемых валют
  public function actionCurrencies()
  {
    Tinkoffinvest::StartClient();
    $currencies = Tinkoffinvest::getTksCurrencies($this->client);
    Tinkoffinvest::ClientUnregister();
    return $this->render('currencies', ['currencies' => $currencies]);
  }

  public function actionAccounts () {
    Tinkoffinvest::StartClient();
    $accounts = Tinkoffinvest::getTksAccounts();
    Tinkoffinvest::ClientUnregister();
    return $this->render('accounts', ['a' => $accounts]);
  }

  public function actionPortfolio() {
    Tinkoffinvest::StartClient();
    $portfolio = Tinkoffinvest::getTksPortfolio();
    Tinkoffinvest::ClientUnregister();
    return $this->render('portfolio', ['p' => $portfolio]);
  }

  /**
   * Баланс счета по заданной валюте-параметру
   * 
   * @param $TICurrencyEnum string
   */
  public function actionPortfoliocurrencybalance ($TICurrencyEnum='USD') {
    Tinkoffinvest::StartClient();
    $PortfolioCurrencyBalance = Tinkoffinvest::getTksCurrencyBalance($TICurrencyEnum);
    Tinkoffinvest::ClientUnregister();
    return $this->render('currencybalance', 
    [
      'p' => $PortfolioCurrencyBalance,
      'c' => $TICurrencyEnum,
    ]);;
  }

}