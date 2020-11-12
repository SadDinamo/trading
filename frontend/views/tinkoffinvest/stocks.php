<?php
use yii\helpers\Html;

$this->title = 'Stocks provided by API';
?>

<div>
  <h1><?= Html::encode($this->title) ?></h1>
  <?
  echo '<pre>';
  var_dump($stocks);
  echo '<\pre>';
  ?>
</div>