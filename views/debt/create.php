<?php

/**
 * @var $this yii\web\View
 * @var $model app\models\Debt
 * @var $user app\models\User[]
 * @var $currency app\models\Currency[]
 */
?>
<div class="debt-create">
    <?= $this->render('_form', [
        'model' => $model,
        'user' => $user,
        'currency' => $currency,
    ]) ?>
</div>
