<?php
/* @var $this yii\web\View */

$this->title = 'Тайный Санта';
?>
<div class="site-index">
    <div class="navbar alert-dark h3">Привет, <?= $user->name ?>! <a href="<?= \Yii::$app->urlManager->createUrl(['site/logout']) ?>">Выйти</a></div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 ">

                <?php if ($user->isSanta()) { ?> 
                <p> Ты <?=$user->isMan() ? 'выбрал' : 'выбрала'?>, кому дарить подарок:</p>
                        <div class="card float-left" style="width: 220px; height: 320px; text-align: center; vertical-align: middle; background-color: lightblue">
                            <h2 style="color: red"><?= $user->getTarget()->one()->name ?></h2>                                
                        </div>
                    <p class="clearfix"></p>
                    <div class="card" id="wishlist">
                        <h4>Вишлист:</h4>
                        <?= nl2br($user->getTarget()->one()->wishlist ?? 'Пока не заполнен'); ?> 
                    </div>
                <?php } else { ?>
                    <p> Выбери, кому дарить подарок!</p>
                <?php } ?>
                <div>
                    <?php foreach ($availableTargets as $target) { ?>
                        <div class="card float-left" style="width: 220px">
                            <a style="margin: 10px" href="<?= \Yii::$app->urlManager->createUrl(['site/pair', 'key' => $target->public_id]) ?>">
                                <img src="img/Somebody.png" alt="?"/>
                            </a>
                        </div>
                    <?php } ?>
                </div>

            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body" style="background-color: lightblue">
                        <h3 class="card-title">Ваш Тайный Санта</h3>
                        <?php if ($user->hasSanta()) { ?> 
                            <p>Поздравляем, у вас уже есть Тайный Санта!</p>
                        <?php } else { ?>
                            <p>У вас еще нет Тайного Санты.</p>
                        <?php } ?>
                    </div>
                </div>
                
                <a class="btn-primary" href="<?= \Yii::$app->urlManager->createUrl(['site/wishlist']) ?>">Твой вишлист</a>

            </div>
        </div>
    </div>

</div>
