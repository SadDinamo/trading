<?php
use yii\helpers\Html;
use common\models\TksPreferencies;

$this->title = TksPreferencies::findByPreferenceName('ClientType')->Value .
  ' portfolio';
?>

<div>
  <h1><?= Html::encode($this->title) ?></h1>
  <?
  echo '<pre>';
  var_dump($p);
  echo '<\pre>';
  ?>
</div>