<?php

namespace app\models;

use Yii;
use yii\db\Query;
use app\models\Tools;
use app\models\Validate;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface{
    public static function tableName(){
        return 'user';
    }

    public static function findIdentity($id){
        return static::findOne($id);
    }
    public static function findIdentityByAccessToken($token, $type = null){
        return static::findOne(['access_token' => $token]);
    }

    public function getId(){
        return $this->id;
    }
    public function getAuthKey(){
        return $this->auth_key;
    }
    public function validateAuthKey($authKey){
        return $this->getAuthKey() === $authKey;
    }
    public function validatePassword($password){
        return $this->password === md5($password);
      }

    public static function getById($id){
      if(!$id) return NULL;

      return User::findOne(['id' => $id]);
    }
    public static function getByLogin($login){
      if(!$login) return NULL;

      return User::findOne(['login' => $login]);
    }

    public static function getCurrentIdentity(){
      return Yii::$app->user->identity;
    }

    public static function isLogged(){
      if(!Yii::$app) return false;
      if(!Yii::$app->user) return false;
      if(!Yii::$app->user->identity) return false;

      return true;
    }


    public static function updateUser($id = false, $p){
      $u = $id ? User::getById($id) : new User;

      if($p['username']) $u->login = $p['username'];
      if($p['firstname']) $u->name = $p['firstname'];
      if($p['password']) $u->password = md5($p['password']);

      if(!$id) $u->access_token = Tools::generateRandomString(32);
      if(!$id) $u->auth_key = Tools::generateRandomString(32);

      return $u->save() ? true : false;
    }


    public static function login($login, $password){
      $user = User::getByLogin($login);
      if(!$user){
        Yii::$app->session->addFlash('danger', 'Nie znaleziono użytkownika o wskazanej nazwie użytkownika');
        return false;
      }

      $password_ok = $user->validatePassword($password);
      if($password_ok){
        return Yii::$app->user->login($user);
      } else {
        Yii::$app->session->addFlash('danger', 'Podane hasło jest nieprawidłowe');
        return false;
      };
    }
}
