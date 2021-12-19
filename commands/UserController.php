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
    
}
