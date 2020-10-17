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

  public function actionAllmarketstocks () {
      Tinkoffinvest::startSandboxClient();
      $filterModel = new \jamesRUS52\TinkoffInvest\TIInstrument('','','','','','','','');
      $stocks = Tinkoffinvest::getTksStocks($this->client);
      $results = array();
      foreach ($stocks as $stock) {
        array_push($results,[
          'figi'=>$stock->getFigi(),
          'ticker'=>$stock->getTicker(),
          'isin'=>$stock->getIsin(),
          'minPriceIncrement'=>$stock->getMinPriceIncrement(),
          'lot'=>$stock->getLot(),
          'currency'=>$stock->getCurrency(),
          'name'=>$stock->getName(),
          'type'=>$stock->getType(),
        ]);
      }
      $dataProvider = new ArrayDataProvider([
        'allModels'=>$results,
        'pagination'=>[
          'pageSize'=> 15,
        ],
        'sort'=>[
          'attributes'=>['ticker'],
          'defaultOrder'=>[
            'ticker'=>SORT_ASC,
          ],
        ],
      ]);
      Tinkoffinvest::sandboxClientUnregister();
      return $this->render('allmarketstocks', [
          'dataProvider' => $dataProvider,
          'filterModel' => $filterModel,
      ]);
  }

  // Получение массива торгуемых акций
  public function actionStocks()
  {
    Tinkoffinvest::startSandboxClient();
    $stocks = Tinkoffinvest::getTksStocks($this->client);
    Tinkoffinvest::sandboxClientUnregister();
    return $this->render('stocks', ['stocks'=>$stocks]);
  }

  // Получение массива торгуемых облигаций
  public function actionBonds () {
    Tinkoffinvest::startSandboxClient();
    $bonds = Tinkoffinvest::getTksBonds($this->client);
    Tinkoffinvest::sandboxClientUnregister();
    return $this->render('bonds', ['bonds'=>$bonds]);
  }

  // Получение массива торгуемых ETF
  public function actionEtfs()
  {
    Tinkoffinvest::startSandboxClient();
    $etfs = Tinkoffinvest::getTksEtfs($this->client);
    Tinkoffinvest::sandboxClientUnregister();
    return $this->render('etfs', ['etfs' => $etfs]);
  }

  // Получение массива торгуемых валют
  public function actionCurrencies()
  {
    Tinkoffinvest::startSandboxClient();
    $currencies = Tinkoffinvest::getTksCurrencies($this->client);
    Tinkoffinvest::sandboxClientUnregister();
    return $this->render('currencies', ['currencies' => $currencies]);
  }

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

  /** 
  * https://github.com/jamesRUS52/tinkoff-invest
  * 
  * //TODO::
  * разобраться со свечами из GetHistoricalCandles
  * Get accounts
  * Get portfolio (if null, used default Tinkoff account)
  * Get portfolio balance
  * Get instrument lots count
  * Send limit order (default brokerAccountId = Tinkoff)
  * Send market order (default brokerAccountId = Tinkoff)
  * Cancel order
  * List of operations from 10 days ago to 30 days period
  * Getting instrument status
  * Get Candles and Order books
  * 
  * SUPER: subscribe on changes order books
  *
  */



  public function actionAccounts () {
    Tinkoffinvest::startSandboxClient();
    $accounts = Tinkoffinvest::getTksAccounts();
    return $this->render('accounts', ['a' => $accounts]);
  }


  public function actionPortfolio()
  {
    Tinkoffinvest::startSandboxClient();
    $portfolio = Tinkoffinvest::getTksPortfolio();
    return $this->render('portfolio', ['p' => $portfolio]);
  }


}
