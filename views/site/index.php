<?php

/* @var $this yii\web\View */

$this->title = 'Тайный Санта';
?>
<div class="site-index">
    <div class="navbar alert-dark">Здравствуйте, <?=$user->name?>! <a href="<?=\Yii::$app->urlManager->createUrl(['site/logout']) ?>">Выйти</a></div>

    <?php if ($user->hasSanta()) { ?> 
        <p>Поздравляем, у вас уже есть Тайный Санта!</p>
    <?php } else { ?>
        <p>У вас еще нет Тайного Санты.</p>
    <?php } ?>

    
    <?php if ($user->isSanta()) { ?> 
        <p> Вы выбрали, кому дарить подарок: <?=$user->getTarget()->one()->name ?></p>
        <h2>Вишлист:</h2>
        <p><?= $user->getTarget()->one()->wishlist ?? 'Пока не заполнен' ?> </p>
    <?php } else { ?>
        <p> Выберите, кому дарить подарок!</p>
    <?php } ?>
    <ul>
    <?php foreach ($availableTargets as $target) { ?>
        <li><a href="<?=\Yii::$app->urlManager->createUrl(['site/peer', 'key' => $target->public_id]) ?>">?????</a></li>
    <?php } ?>
    </ul>
</div>
