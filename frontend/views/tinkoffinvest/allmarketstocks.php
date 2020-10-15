<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'All Market Stocks provided by API';
?>

<div>
<h1><?= Html::encode($this->title) ?></h1>
<?

  echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'filterModel'=>$filterModel,
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],
      'figi',
      'ticker',
      'isin',
      'minPriceIncrement',
      'lot',
      'currency',
      'name',
      'type',
    ],
    'summary' => false,
    'pager' => [
      'firstPageLabel' => 'First',
      'lastPageLabel' => 'Last',
      'nextPageLabel' => 'Next',
      'prevPageLabel' => 'Prev',
    ],
  ]);
?>
</div>