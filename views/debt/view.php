<?php

use app\models\Debt;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;

/**
 * @var $this yii\web\View
 * @var $direction integer
 * @var $currencyId integer
 * @var $dataProvider yii\data\ActiveDataProvider
 */

?>
<div class="debt-view">
    <div class="card">
        <div class="card-header d-flex p-0">
            <ul class="nav nav-pills ml-auto p-2">
                <li class="nav-item">
                    <?= Html::a(
                        Yii::t('debt', 'My Deposits'),
                        [
                            'debt/view',
                            'direction' => Debt::DIRECTION_DEPOSIT,
                            'currencyId' => $currencyId,
                        ],
                        [
                            'class' => 'nav-link show ' . ($direction === Debt::DIRECTION_DEPOSIT ? 'active' : ''),
                        ]
                    ) ?>
                </li>
                <li class="nav-item">
                    <?= Html::a(
                        Yii::t('debt', 'My Credits'),
                        [
                            'debt/view',
                            'direction' => Debt::DIRECTION_CREDIT,
                            'currencyId' => $currencyId,
                        ],
                        [
                            'class' => 'nav-link show ' . ($direction === Debt::DIRECTION_CREDIT ? 'active' : ''),
                        ]
                    ) ?>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'summary' => false,
                'tableOptions' => ['class' => 'table table-hover'],
                'columns' => [
                    [
                        'label' => Yii::t('debt', 'User'),
                        'value' => function (Debt $data) use ($direction) {
                            return $data->getUserDisplayName($direction);
                        },
                        'format' => 'html',
                    ],
                    [
                        'label' => Yii::t('debt', 'Amount'),
                        'value' => function (Debt $data) {
                            return $data->amount ?? null;
                        },
                        'format' => 'html',
                    ],
                    [
                        'label' => Yii::t('debt', 'Created At'),
                        'value' => function (Debt $data) {
                            return $data->created_at ?? null;
                        },
                        'format' => 'relativeTime',
                    ],
                    [
                        'value' => function (Debt $data) {
                            if ($data->isStatusPending()) {
                                return '<span class="badge badge-warning">Pending</span>';
                            }

                            return '';
                        },
                        'format' => 'html',
                    ],
                    [
                        'class' => ActionColumn::class,
                        'template' => '{confirm} {cancel}',
                        'buttons' => [
                            'confirm' => function ($url, Debt $data) use ($direction, $currencyId) {
                                return Html::a(
                                    Yii::t('app', 'Confirm'),
                                    [
                                        'debt/confirm',
                                        'id' => $data->id,
                                        'direction' => $direction,
                                        'currencyId' => $currencyId,
                                    ],
                                    [
                                        'class' => 'btn btn-outline-success',
                                    ]
                                );
                            },
                            'cancel' => function ($url, Debt $data) use ($direction, $currencyId) {
                                return Html::a(
                                    Yii::t('app', 'Cancel'),
                                    [
                                        'debt/cancel',
                                        'id' => $data->id,
                                        'direction' => $direction,
                                        'currencyId' => $currencyId,
                                    ],
                                    [
                                        'class' => 'btn btn-outline-danger',
                                    ]
                                );
                            },
                        ],
                        'visibleButtons' => [
                            'confirm' => function (Debt $data) use ($direction) {
                                return $data->canConfirmDebt($direction);
                            },
                            'cancel' => function (Debt $data) {
                                return $data->canCancelDebt();
                            },
                        ],
                    ],
                ],
                'layout' => "{summary}\n{items}\n<div class='card-footer clearfix'>{pager}</div>",
                'pager' => [
                    'options' => [
                        'class' => 'pagination float-right',
                    ],
                    'linkContainerOptions' => [
                        'class' => 'page-item',
                    ],
                    'linkOptions' => [
                        'class' => 'page-link',
                    ],
                    'maxButtonCount' => 5,
                    'disabledListItemSubTagOptions' => [
                        'tag' => 'a',
                        'class' => 'page-link',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
