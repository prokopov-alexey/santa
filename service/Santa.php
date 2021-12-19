<?php

namespace app\service;

use app\models\db\User;
use yii\base\InvalidArgumentException;

class Santa {
    
    public function getAllAvailableTargets(User $santa): array
    {
        if ($santa->getTarget()->one() !== null) {
            return [];
        }
        
        $users = User::findBySql(
           'SELECT * FROM user WHERE santa_id IS NULL AND id <> :id', 
           [':id' => $santa->id]
        )->all();
        
        shuffle($users);
        
        return $users;
    }

    public function peer(User $santa, User $target)
    {
        if ($santa->id === $target->id) {
            throw InvalidArgumentException('No one can be a Santa for himself!');
        }
        
        if ($santa->getTarget()->exists()) {
            throw InvalidArgumentException('The Santa already has a target!');
        }

        if ($target->getSanta()->exists()) {
            throw InvalidArgumentException('The target already has a Santa!');
        }
        
        $target->santa_id = $santa->id;
        
        $target->save();
    }
    
    public function getRandomKey() {
        return md5(rand(0, 1000000));
    }
    
    public function createUser($name, $sex) {
        $user = new User();
        
        $user->name = $name;
        $user->sex = $sex;
        $user->secret_id = $this->getRandomKey();
        $user->public_id = $this->getRandomKey();
        
        return $user->save();
    }
    
}
