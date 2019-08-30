<?php


namespace app\models;


use yii\db\ActiveRecord;

/**
 * Class User
 * @package app\models
 * @property int $ID
 * @property string $login
 * @property string $name_last
 * @property string $name_first
 */
class User extends ActiveRecord
{
    
    public static function tableName()
    {
        return 'users';
    }
} 