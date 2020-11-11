<?php
use yii\helpers\Html;

$this->title = 'Currencies provided by API';
?>

<div>
  <h1><?= Html::encode($this->title) ?></h1>
  <?
  echo '<pre>';
  var_dump($currencies);
  echo '</pre>';
  ?>
</div>