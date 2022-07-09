<? use yii\helpers\Url; use app\models\User;

function hasActiveChildren($items){
  if(!$items || count($items) == 0) return false;
  foreach ($items as $key => $item) {
    $controller = $item["controller"] ? $item["controller"] : false;
    if($controller === Yii::$app->controller->id) return true;
  }

  return false;
}

function createItem($item, $depth){
    $icon = isset($item["i"]) ? $item['i'] : "";
    $controller = isset($item["c"]) ? $item["c"] : false;
    $action = isset($item["a"]) ? $item["a"] : "index";
    $href = Url::to([$controller."/".$action]);
    $name = $item["n"];
    $content = isset($item["ct"]) ? $item["ct"] : false;
    $random = rand(100000,999999);
    $active = ($controller == "site" && Yii::$app->controller->id == $controller && Yii::$app->controller->action->id == $action) || ($controller != "site" && Yii::$app->controller->id == $controller ) ? true : false;
    $active_child = hasActiveChildren($content);
    $home = $controller == "site" && $action == "index" ? true : false;
    ?>


    <a class="menu-item <? if($active || $active_child){echo 'active ';} if($home){echo 'home ';}?>" href="<? if(!$content){echo $href;}else{echo "#sub_menu_".$random;} ?>" title="<?= $name ?>" data-depth="<?= $depth ?>" <? if($content){echo 'data-toggle="collapse" role="button" aria-expanded="'.($active_child ? "true" : "false").'" aria-controls="sub_menu_'.$random.'"';} ?>>
      <i class="menu-item-logo <?= $icon ?>"></i>
      <div class="menu-item-text">
        <?= $name ?>

        <? if(isset($content) && $content){ ?>
          <i class="fas fa-caret-down"></i>
        <? } ?>

      </div>
    </a>


    <? if(isset($content) && $content){?>
    <div class="sub-menu collapse <?= $active_child ? "show" : "" ?>" id="sub_menu_<?= $random ?>" data-parent="#menu">
      <? foreach ($content as $key => $subitem){
        if($subitem != NULL){
          createItem($subitem, $depth+1);
        }
      } ?>
    </div>
    <? } ?>
  <?
}

$menu = array(
          ["i" => "fas fa-presentation", "n" => "Dashboard", "c" => "site", "a" => "index"],
          ["i" => "separator"],
          ["i" => "fas fa-briefcase", "n" => "Projekty", "c" => "project", "a" => "index"],
          ["i" => "fas fa-file-word", "n" => "Silosy wiedzy", "c" => "knowledge", "a" => "index"],
          ["i" => "fas fa-clipboard", "n" => "Notatki", "c" => "notes", "a" => "index"],
          ["i" => "fas fa-link", "n" => "Przydatne linki", "c" => "hyperlinks", "a" => "index"],
          ["i" => "fas fa-key", "n" => "Dane dostępowe", "c" => "password", "a" => "index"],
          ["i" => "fas fa-clock", "n" => "Godziny pracy", "c" => "schedule", "a" => "index"],
          ["i" => "separator"],
          ["i" => "fas fa-user", "n" => "Zespół", "c" => "team", "a" => "index"],
          ["i" => "fas fa-cog", "n" => "Ustawienia", "c" => "settings", "a" => "index"],
          ["i" => "separator"],
          ["i" => "fas fa-sign-out", "n" => "Wyloguj", "c" => "site", "a" => "logout"],

          // array("icon" => "data", "name" => "Projekty", "content" => array(
          //                                                                       array("icon" => "teacher", "name" => "Lista projektów", "controller" => "project", "action" => "index"),
          //                                                                       array("icon" => "group", "name" => "Dodaj projekt", "controller" => "project", "action" => "create"),
          //                                                                       array("icon" => "student", "name" => "Archiwum projektów", "controller" => "project", "action" => "archive"),
          //                                                                       // array("icon" => "cockpit", "name" => "Spotkania", "controller" => "meeting", "action" => "index"),
          //                                                                       )),
          //
          // // array("icon" => "separator"),
          // array("icon" => "teacher", "name" => "Zmień hasło", "controller" => "site", "action" => "changepassword"),
          // array("icon" => "logout", "name" => "Wyloguj", "controller" => "site", "action" => "logout"),


);
foreach ($menu as $key => $section) {
  $depth = 0;
  if($section["i"] == "separator")
      echo '<div class="separator"></div>';
  else
    if($section != NULL) createItem($section, $depth);
}
