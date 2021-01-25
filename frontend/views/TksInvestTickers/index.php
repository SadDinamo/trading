<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TksInvestTickersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список тикеров';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tks-invest-tickers-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => 'Показано {count} из {totalCount} тикеров. Страница {page} из {pageCount}.',
        'summaryOptions' => ['class' => 'bg-primary text-white',],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //'id',
            'figi',
            'ticker',
            'isin',
            'minPriceIncrement',
            'lot',
            'currency',
            [
                'attribute' => 'name',
                'contentOptions' => ['style' => 'width: 35%;']
            ],
            [
                'attribute' => 'type',
            ],
            [
                'attribute' => 'creationDate',
                'contentOptions' => ['style' => 'width: 20%;']
            ],
            'active',
            //'updateDate',
            //['class' => 'yii\grid\ActionColumn'],
        ],
        'pager' => [
            'lastPageLabel' => 'last',
            'firstPageLabel' => 'first',
            'maxButtonCount' => 15,
        ],
        'layout' => "{summary}<br>{pager}\n{items}",

    ]); ?>

    <?php echo yii2mod\alert\Alert::widget(); ?>

    <?php Pjax::end(); ?>

</div>