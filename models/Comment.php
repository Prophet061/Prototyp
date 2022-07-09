<?php namespace app\models;
      use Yii;
      use app\models\User;
      use app\models\Tools;
      use yii\db\Query;
      use yii\db\Expression;


class Comment extends \yii\db\ActiveRecord{
    public static function tableName(){
        return 'comment';
    }
    public static function getById($id){
      if(!$id) return NULL;
      return Comment::findOne(['id' => $id]);
    }

    public static function updateComment($id, $p){
      $comment = $id ? Comment::getById($id) : new Comment();
      $me = User::getCurrentIdentity();

      $comment->id_post = $p['id_post'];
      $comment->login = $p['login'];
      $comment->comment = $p['comment'];

      return $comment->save() ? array('success' => true, 'id' => $comment->id) : array('success' => false);
    }

    public static function getComments($params = [], $records = 40, $page = 1, $countonly = false){
      $offset = ($page - 1) * $records;

      $query = Comment::find()->orderBy(['date_created' => SORT_DESC]);
      if($params) foreach ($params as $k => $v)$query->andWhere($v);

      $query = $countonly ? $query->all() : $query->limit($records)->offset($offset)->all();
      return count($query) > 0 ? ($countonly ? count($query) : $query) : [];
    }
}
