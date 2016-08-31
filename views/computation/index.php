<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ComputationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Расчеты';
$this->params['breadcrumbs'][] = $this->title;
?>


<?php
    yii\bootstrap\Modal::begin(['id' =>'computation-modal']);
    yii\bootstrap\Modal::end();
?>



<div class="computation-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить новый расчет', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            //'text:ntext',
            'mybutton' => [
                'header'=> 'Расчет',
                'format'=> 'raw',
                'value'=> function($data){
                    return '<button type="button" rel="popover" class="btn btn-link" data-trigger="focus" 
                            data-container="body" data-toggle="popover" data-placement="left" data-content="'.Html::encode($data->text).'">
                            Показать
                            </button>';
                }
            ],
            'codesString',
            //'created_at:datetime',
            //'updated_at:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


<?php
$js=<<<JS
$('[rel="popover"]').popover();
JS;
$this->registerJs($js);
?>


<?php Pjax::end(); ?></div>

