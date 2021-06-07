<?php

namespace frontend\controllers;

use Yii;
use app\models\TickerStats;
use app\models\TickerStatsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Parser;
use app\models\TksInvestTickers;

/**
 * TickerstatsController implements the CRUD actions for TickerStats model.
 */
class TickerstatsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TickerStats models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TickerStatsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TickerStats model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TickerStats model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TickerStats();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TickerStats model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TickerStats model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TickerStats model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TickerStats the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TickerStats::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Adds one ticker info from:
     * frontend\controllers\ParserController::yahooTickerInfo($ticker)
     * to 'ticker_stats' db table
     * 
     * @param string $ticker
     */
    public function addTickerInfo($ticker){
        // $tickerInfo = Parser::yahooTickerInfo($ticker);
        $tickerInfo = Parser::getYahooTickerJSON($ticker);
        if (!$tickerInfo['financialData']['financialCurrency']) {
            } else {
            if (TickerStats::find()->where([
                'ticker' => $ticker,
                'date' => $tickerInfo['defaultKeyStatistics']['dateShortInterest']['fmt'],
            ])->one()) { } 
            else {
                $tickerStats = new TickerStats;
                $tickerStats->ticker = $ticker;
                $tickerStats->date = $tickerInfo['defaultKeyStatistics']['dateShortInterest']['fmt'];
                $tickerStats->short_ratio = $tickerInfo[ 'defaultKeyStatistics']['shortRatio']['raw'];
                $tickerStats->short_percent_of_float = $tickerInfo['defaultKeyStatistics']['shortPercentOfFloat']['raw'];
                $tickerStats->ebitda = $tickerInfo['financialData']['ebitda']['raw'];
                $tickerStats->total_cash = $tickerInfo['financialData']['totalCash']['raw'];
                $tickerStats->total_debt = $tickerInfo['financialData']['totalDebt']['raw'];
                $tickerStats->operating_cash_flow = $tickerInfo['financialData']['operatingCashflow']['raw'];
                $tickerStats->save();
            }
        }

        // if (! $tickerInfo['Found'])
        // {} else {
        //     if ($tickerInfo['Found']) {
        //         if (TickerStats::find()->where(['ticker' => $ticker])->andwhere([
        //                 'name' => 'Short Ratio', 
        //                 'date' => $tickerInfo['Short Ratio']['date'],
        //             ])->one()) {
        //         } else {
        //             $tickerStats = new TickerStats;
        //    \\         $tickerStats->ticker = $ticker;
        //             $tickerStats->name = 'Short Ratio';
        //             $tickerStats->date = $tickerInfo['Short Ratio']['date'];
        //             $tickerStats->value = $tickerInfo['Short Ratio']['value'];
        //             //var_dump($tickerStats);
        //             $tickerStats->save();
        //         }
        //         if (TickerStats::find()->where(['ticker' => $ticker])->andwhere([
        //             'name' => 'Total Cash (mrq)',
        //             'date' => $tickerInfo[ 'Total Cash (mrq)']['date'],
        //         ])->one()) { } else {
        //             $tickerStats = new TickerStats;
        //             $tickerStats->ticker = $ticker;
        //             $tickerStats->name = 'Total Cash (mrq)';
        //             $tickerStats->date = $tickerInfo[ 'Total Cash (mrq)']['date'];
        //             $tickerStats->value = $tickerInfo[ 'Total Cash (mrq)']['value'];
        //             //var_dump($tickerStats);
        //             $tickerStats->save();
        //         }
        //         if (TickerStats::find()->where(['ticker' => $ticker])->andwhere([
        //             'name' => 'Total Debt (mrq)',
        //             'date' => $tickerInfo['Total Debt (mrq)']['date'],
        //         ])->one()) { } else {
        //             $tickerStats = new TickerStats;
        //             $tickerStats->ticker = $ticker;
        //             $tickerStats->name = 'Total Debt (mrq)';
        //             $tickerStats->date = $tickerInfo['Total Debt (mrq)']['date'];
        //             $tickerStats->value = $tickerInfo['Total Debt (mrq)']['value'];
        //             //var_dump($tickerStats);
        //             $tickerStats->save();
        //         }
        //         if (TickerStats::find()->where(['ticker' => $ticker])->andwhere([
        //             'name' => 'Short % of Float',
        //             'date' => $tickerInfo['Short % of Float']['date'],
        //         ])->one()) { } else {
        //             $tickerStats = new TickerStats;
        //             $tickerStats->ticker = $ticker;
        //             $tickerStats->name = 'Short % of Float';
        //             $tickerStats->date = $tickerInfo['Short % of Float']['date'];
        //             $tickerStats->value = $tickerInfo['Short % of Float']['value'];
        //             //var_dump($tickerStats);
        //             $tickerStats->save();
        //         }
        //         if (TickerStats::find()->where(['ticker' => $ticker])->andwhere([
        //             'name' => 'Operating Cash Flow (Trailing Twelve Months)',
        //             'date' => $tickerInfo['Operating Cash Flow (Trailing Twelve Months)']['date'],
        //         ])->one()) { } else {
        //             $tickerStats = new TickerStats;
        //             $tickerStats->ticker = $ticker;
        //             $tickerStats->name = 'Operating Cash Flow (Trailing Twelve Months)';
        //             $tickerStats->date = $tickerInfo['Operating Cash Flow (Trailing Twelve Months)']['date'];
        //             $tickerStats->value = $tickerInfo['Operating Cash Flow (Trailing Twelve Months)']['value'];
        //             //var_dump($tickerStats);
        //             $tickerStats->save();
        //         }
        //     }
        // }
    }

    /**
     * Adds all tickers info for each ticker in db
     * 
     */
    public function addTickersInfo () {
        set_time_limit(60*60*2); // 2*60 minutes to run
        $tickersArray = TksInvestTickers::find()->select('ticker')->where(['active'=>1,'type'=>'Stock'])->all();
        foreach($tickersArray as $ticker){
            TickerstatsController::addTickerInfo($ticker->ticker);
        };
        return;
    }

    /**
     * test - //TODO:: to delete
     */
    // public function actionAddtickerinfo(){
    //     $this->addTickerInfo($ticker);
    // }
    public function actionAddtickersinfo()
    {
        $this->addTickersInfo();
    }
}
