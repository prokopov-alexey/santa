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
                <p> Ты <?=$user->isMan() ? 'выбрал' : 'выбрала'?>, кому дарить подарок: <span class="badge-info"><?= $user->getTarget()->one()->name ?></span></p>


                    <button class="btn btn-primary" data-toggle="collapse" data-target="#wishlist">Вишлист:</button>

                    <div class="collapse card" id="wishlist"><?= $user->getTarget()->one()->wishlist ?? 'Пока не заполнен' ?> </div>
                <?php } else { ?>
                    <p> Выбери, кому дарить подарок!</p>
                <?php } ?>
                <div>
                    <?php foreach ($availableTargets as $target) { ?>
                        <div class="card float-left" style="width: 220px">
                            <a style="margin: 10px" href="<?= \Yii::$app->urlManager->createUrl(['site/peer', 'key' => $target->public_id]) ?>">
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

            </div>
        </div>
    </div>

</div>
