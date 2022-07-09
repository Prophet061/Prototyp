<? use app\assets\AppAsset; use app\widgets\Alert; use app\models\User; AppAsset::register($this); $this->beginPage();

$navbar = isset($_COOKIE['navbar-state']) && $_COOKIE['navbar-state'] == "shown" ? true : false;
$c = Yii::$app->controller->id;
$a = Yii::$app->controller->action->id;

?>

  <!DOCTYPE html>
  <html lang="<?= Yii::$app->language ?>" class="h-100">

  <head>
    <title><?= $this->title ?> - <?= Yii::$app->params['appname'] ?></title>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?= $this->registerCsrfMetaTags() ?>
    <?= $this->head() ?>
  </head>

  <body class="<?= $navbar ? "navbar-active " : ""?><?= $a == "login" ? "ps-0" : "" ?>" id="<?= $c ?>-<?= $a ?>">
    <? $this->beginBody() ?>
    <? if($a != "login") { ?>
    <div class="menu py-5 sc-med-to-bright-menu" id="menu">
      <a class="menu-item" onclick="toggleNavbar()"  title="Dashboard" data-depth="0">
        <i class="menu-item-logo fas fa-bars" aria-hidden="true"></i>
        <div class="menu-item-text">ProjectBOX</div>
      </a>
      <?= $this->render('menu');?>
    </div>
    <? } ?>

    <div id="content" class="container-fluid mt-5 sc-med-to-bright">
      <?= $content ?>
    </div>
  <? $this->endBody() ?>
  </body>

  <script type="text/javascript">
    $(document).ready(function(){
      $("#modal").modal('show')
    });
  </script>
  </html>

<? $this->endPage() ?>
