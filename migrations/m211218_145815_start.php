<?php

use yii\db\Migration;

/**
 * Class m211218_145815_start
 */
class m211218_145815_start extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('
            CREATE TABLE `user` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) DEFAULT NULL,
            `sex` ENUM(\'M\',\'F\') NOT NULL,
            `public_id` char(32) NOT NULL,
            `secret_id` char(32) NOT NULL,
            `santa_id` int(11) DEFAULT NULL,
            `wishlist` text DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `name_UNIQUE` (`name`),
            UNIQUE KEY `public_id_UNIQUE` (`public_id`),
            UNIQUE KEY `secret_id_UNIQUE` (`secret_id`),
            UNIQUE KEY `santa_id_UNIQUE` (`santa_id`),
            CONSTRAINT `fk_user_santa_id_user_id` FOREIGN KEY (`santa_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
            ) ENGINE=InnoDB;
        ');

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('DROP TABLE user;');
        
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211218_145815_start cannot be reverted.\n";

        return false;
    }
    */
}
