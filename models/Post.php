<?php namespace app\models;
      use Yii;
      use app\models\User;
      use app\models\Tools;
      use yii\db\Query;
      use yii\db\Expression;


class Post extends \yii\db\ActiveRecord{
    public static function tableName(){
        return 'post';
    }
    public static function getById($id){
      if(!$id) return NULL;
      return Post::findOne(['id' => $id]);
    }

    public function isOwner(){
      $me = User::getCurrentIdentity();
      if(!$me) return false;

      return $this->id_user == $me->id ? true : false;
    }

    public static function updatePost($id, $p){
      $post = $id ? Post::getById($id) : new Post();
      $me = User::getCurrentIdentity();
      $failed = false;

      if(strlen($p['title']) < 3){
        $failed = true;
        Yii::$app->session->addFlash('danger', 'Podany tytuł jest niepoprawny');
      }

      if(strlen($p['content']) < 3){
        $failed = true;
        Yii::$app->session->addFlash('danger', 'Podana treść jest niepoprawna');
      }

      if(strlen($p['excerpt']) < 3){
        $failed = true;
        Yii::$app->session->addFlash('danger', 'Podana zajawka jest niepoprawna');
      }

      if(strlen($p['cover']) < 3){
        $failed = true;
        Yii::$app->session->addFlash('danger', 'Podana okładka jest niepoprawna');
      }


      if(!$id) $post->id_owner = $me->id;
      $post->title = $p['title'];
      $post->content = $p['content'];
      $post->excerpt = $p['excerpt'];
      $post->cover = $p['cover'];

      return !$failed && $post->save() ? array('success' => true, 'id' => $post->id) : array('success' => false);
    }
    public static function getPosts($params = [], $records = 40, $page = 1, $countonly = false){
      $offset = ($page - 1) * $records;

      $query = Post::find()->orderBy(['date_created' => SORT_DESC]);

      if($params) foreach ($params as $v) $query->andWhere($v);

      $query = $countonly ? $query->all() : $query->limit($records)->offset($offset)->all();
      return count($query) > 0 ? ($countonly ? count($query) : $query) : [];
    }

    public static function getRandomPosts(){
      $q = Post::find()->orderBy(new Expression('rand()'))->limit(3);

      return $q->all();
    }
}
