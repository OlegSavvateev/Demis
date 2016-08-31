<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Computation */

$this->title = 'Добавить расчет';
$this->params['breadcrumbs'][] = ['label' => 'Расчеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="computation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
