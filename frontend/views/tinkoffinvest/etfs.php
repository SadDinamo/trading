<?php
use yii\helpers\Html;

$this->title = 'ETFs provided by API';
?>

<div>
  <h1><?= Html::encode($this->title) ?></h1>
  <?
  echo '<pre>';
  var_dump($etfs);
  echo '<\pre>';
  ?>
</div>