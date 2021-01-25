<?php

namespace frontend\controllers;

use Yii;
use app\models\TksInvestTickers;
use app\models\TksInvestTickersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Tinkoffinvest;

/**
 * TksinvesttickersController implements the CRUD actions for TksInvestTickers model.
 */
class TksinvesttickersController extends Controller
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
     * Lists all TksInvestTickers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TksInvestTickersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TksInvestTickers model.
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
     * Creates a new TksInvestTickers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TksInvestTickers();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TksInvestTickers model.
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
     * Deletes an existing TksInvestTickers model.
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
     * Finds the TksInvestTickers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TksInvestTickers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TksInvestTickers::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Запись массива тикеров в таблицу tks_invest_tickers
     */
    public function actionTickerstodb()
    {
        Tinkoffinvest::StartClient();
        $stocks = Tinkoffinvest::getTksStocks();
        $bonds = Tinkoffinvest::getTksBonds();
        $etfs = Tinkoffinvest::getTksEtfs();
        $currencies = Tinkoffinvest::getTksCurrencies();
        $tickers = array_merge($stocks, $bonds, $etfs, $currencies);
        Tinkoffinvest::ClientUnregister();

        $dbTickers = TksInvestTickers::find()->all();
        foreach ($dbTickers as $dbTicker) {
            $dbTicker->active = 0;
            $dbTicker->save();
        }

        foreach ($tickers as $ticker) {
            if ((TksInvestTickers::find()->where(['figi' => $ticker->getFigi()])->one()) AND
                (substr($ticker->getTicker(), -4) != '_old')) {
                $TksInvestTicker = TksInvestTickers::find()->where(['figi' => $ticker->getFigi()])->one();
                $TksInvestTicker->active = 1;
                $TksInvestTicker->save();
                } 
            else {
                if (substr($ticker->getTicker(),-4)!='_old') {
                    $TksInvestTicker = new TksInvestTickers();
                    $TksInvestTicker->figi = $ticker->getFigi();
                    $TksInvestTicker->ticker = $ticker->getTicker();
                    $TksInvestTicker->isin = $ticker->getIsin();
                    $TksInvestTicker->minPriceIncrement = $ticker->getMinPriceIncrement();
                    $TksInvestTicker->lot = $ticker->getLot();
                    $TksInvestTicker->currency = $ticker->getCurrency();
                    $TksInvestTicker->name = $ticker->getName();
                    $TksInvestTicker->type = $ticker->getType();
                    $TksInvestTicker->active = 1;
                    $tempDate = date('Y-m-d H:i:s', getdate()['0']);
                    $TksInvestTicker->creationDate = $tempDate;
                    $TksInvestTicker->updateDate = $tempDate;
                    $TksInvestTicker->save();
                }
            }
        };

        Yii::$app->session->setFlash('success', 'Список тикеров обновлен');

        return $this->redirect(['index']);
    }

}