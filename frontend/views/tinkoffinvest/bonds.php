<?php
use yii\helpers\Html;

$this->title = 'Bonds provided by API';
?>

<div>
  <h1><?= Html::encode($this->title) ?></h1>
  <?
  echo '<pre>';
  var_dump($bonds);
  echo '</pre>';
  ?>
</div>