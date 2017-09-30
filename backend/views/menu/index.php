<?php
/**
 * Author: lf
 * Blog: https://blog.feehi.com
 * Email: job@feehi.com
 * Created at: 2016-03-21 14:14
 */

/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ArrayDataProvider
 * @var $searchModel backend\models\MenuSearch
 */

use backend\grid\DateColumn;
use backend\grid\GridView;
use backend\widgets\Bar;
use yii\helpers\Html;
use yii\helpers\Url;
use common\libs\Constants;
use backend\models\Menu;
use backend\grid\CheckboxColumn;
use backend\grid\ActionColumn;

$this->title = "Backend Menus";
$this->params['breadcrumbs'][] = yii::t('app', 'Backend Menus');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox">
            <?= $this->render('/widgets/_ibox-title') ?>
            <div class="ibox-content">
                <?= Bar::widget() ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'class' => CheckboxColumn::className(),
                        ],
                        [
                            'attribute' => 'name',
                            'label' => yii::t('app', 'Name'),
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $column) {
                                return str_repeat("--", $model['level'] - 1) . $model['name'];
                            }
                        ],
                        [
                            'attribute' => 'icon',
                            'label' => yii::t('app', 'Icon'),
                            'format' => 'html',
                            'value' => function ($model) {
                                return "<i class=\"fa {$model['icon']}\"></i>";
                            }
                        ],
                        [
                            'attribute' => 'url',
                            'label' => yii::t('app', 'Url'),
                        ],
                        [
                            'attribute' => 'sort',
                            'label' => yii::t('app', 'Sort'),
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::input('number', "sort[{$model['id']}]", $model['sort']);
                            }
                        ],
                        [
                            'attribute' => 'is_display',
                            'label' => yii::t('app', 'Is Display'),
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) {
                                $menu = new Menu();
                                return Html::a(Constants::getYesNoItems($model['is_display']), ['update', 'id' => $model['id']], [
                                    'class' => 'btn btn-xs btn-rounded ' . ( $model['is_display'] == Menu::DISPLAY_YES ? 'btn-info' : 'btn-default' ),
                                    'data-confirm' => $model['is_display'] == Menu::DISPLAY_YES ? Yii::t('app', 'Are you sure you want to disable this item?') : Yii::t('app', 'Are you sure you want to enable this item?'),
                                    'data-method' => 'post',
                                    'data-pjax' => '0',
                                    'data-params' => [
                                        $menu->formName() . '[is_display]' => $model['is_display'] == Menu::DISPLAY_YES ? Menu::DISPLAY_NO : Menu::DISPLAY_YES
                                    ]
                                ]);
                            },
                            'filter' => Constants::getYesNoItems(),
                        ],
                        [
                            'class' => DateColumn::className(),
                            'attribute' => 'created_at',
                            'label' => yii::t('app', 'Created At'),
                        ],
                        [
                            'class' => DateColumn::className(),
                            'attribute' => 'updated_at',
                            'label' => yii::t('app', 'Updated At'),
                        ],
                        [
                            'class' => ActionColumn::className(),
                            'width' => '190px',
                            'buttons' => [
                                'create' => function ($url, $model, $key) {
                                    return Html::a('<i class="fa  fa-plus" aria-hidden="true"></i> ' . Yii::t('app', 'Create'), Url::to([
                                        'create',
                                        'parent_id' => $model['id']
                                    ]), [
                                        'title' => Yii::t('app', 'Create'),
                                        'data-pjax' => '0',
                                        'class' => 'btn btn-white btn-sm J_menuItem',
                                    ]);
                                }
                            ],
                            'template' => '{create} {update} {delete}',
                        ]
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>