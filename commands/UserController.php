<?php

namespace app\commands;

use \yii\console\Controller;
use app\models\db\User;
use yii\console\ExitCode;
use app\service\Santa;

class UserController extends Controller {

    public function actionCreate($name, $sex) {
        $service = new Santa();
        
        if($service->createUser($name, $sex)) {
            return ExitCode::OK;
        } else {
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
    
    public function actionList() {
        
        $users = User::find()->all();
        
        foreach ($users as $user) {
            echo "{$user->name}\t{$user->secret_id}\n";
        }
        
        return ExitCode::OK;
    }

    public function actionStat() {
        
        /* @var $db \yii\db\Connection */
        $db = \Yii::$app->db;
        
        $totalUsersWithSantaCount = $db->createCommand('SELECT COUNT(*) FROM `user`')->queryScalar();
        $usersWithSantaCount = $db->createCommand('SELECT COUNT(*) FROM `user` WHERE santa_id IS NOT NULL')->queryScalar();
        $usersChosenByThemselves = $db->createCommand('SELECT COUNT(*) FROM `user` WHERE santa_id IS NOT NULL AND santa_id = id')->queryScalar();
        $usersCreatedWishlists = $db->createCommand('SELECT COUNT(*) FROM `user` WHERE wishlist IS NOT NULL')->queryScalar();
        $usersWithSameSanta = $db->createCommand('SELECT COUNT(*) FROM `user` u1 INNER JOIN `user` u2 ON u1.santa_id IS NOT NULL AND u2.santa_id IS NOT NULL AND u2.santa_id=u1.santa_id WHERE u1.id <> u2.id')->queryScalar();

        echo "Total users: $totalUsersWithSantaCount\n";
        echo "Users chosen by Santa: $usersWithSantaCount\n";
        echo "Users, created wishlist: $usersCreatedWishlists\n";
        echo "Users, chosen by themselves: $usersChosenByThemselves\n";
        echo "Users, chosen by the same Santa: $usersWithSameSanta\n";
        
        return ExitCode::OK;
    }
    
}
