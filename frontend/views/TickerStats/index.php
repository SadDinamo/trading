<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TickerStatsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ticker Stats';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticker-stats-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Ticker Stats', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            ['attribute' => 'ticker', 'contentOptions' => ['class' => 'tickerClass']],
            'name',
            'date',
            'value',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>

<?php
$jsTickerHover = <<<JS
    var tickers = document.querySelectorAll('.tickerClass');
    for (var i = 0; i < tickers.length; i++) {
        tickers[i].onclick = function() {
            console.log('click');
        }
        tickers[i].addEventListener('mouseenter', function(event) {
            console.log('mouseenter ' + event.toElement.innerHTML);
            var senddata = event.toElement.innerHTML;
            $.ajax({
                url: '/tickerstats/index',
                type: 'POST',
                data: senddata,
                success: function(receivedata){
                    //console.log(receivedata);
                    console.log('Ajax response received from server for senddata = ' + senddata);
                },
                error: function(){
                    alert('Error sending Ajax');
                }
            });
            return false;
        });
        tickers[i].addEventListener('mouseleave', function(event) {
            console.log('mouseleave ' + event.fromElement.innerHTML);
        });

        // tickers[i].addEventListener('mouseover', function(event) {
        //     console.log('mouseover ' + event.toElement.innerHTML);
        // });

    }
JS;

$this->registerJs($jsTickerHover);

var_dump($dataProvider->getModels());

?>