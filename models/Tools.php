<?php namespace app\models;
      use Yii;

class Tools{
  public static function generateRandomString($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) $randomString .= $characters[rand(0, $charactersLength - 1)];
    return $randomString;
  }
  public static function getDomainFromURL($url) {
    return parse_url($url)['host'] ? parse_url($url)['host'] : $url;
  }
  public static function formatNiceURL($url, $new = false, $title = false){
    if(!$title) $title = "Przejdź do strony ".Tools::getDomainFromURL($url);

    $href = "<a href='".$url."' ".($new ? "target='_blank'" : "")." title='".$title."'>".Tools::truncate($url, 48)."</a>";
    return $href;
  }

  public static function formatRawMoney($money){
    $money = preg_replace('/[^0-9,.]/', '', $money);
    $money = str_replace(",", ".", $money);
    return $money;
  }
  public static function formatMoney($money){
    return number_format($money, 2)." PLN";
  }

  public static function number_format($text){
    $text = (float)$text;
    $text = str_replace(",", ".", $text);

    return $text;
  }

  public static function truncate($string, $length, $dots = "..."){
    return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
  }
  public static function merge($arrays = []){
    $return = [];
    if($arrays) foreach ($arrays as $a) {
      if($a) foreach ($a as $k => $v) $return[$k] = $v;
    }

    return $return;
  }
  public static function sanitize($text){
    $text = strip_tags($text);
    $text = htmlspecialchars($text);
    $text = trim($text);
    $text = addslashes($text);
    return $text;
  }
  public static function getDayOfWeek($day){
    $days = array(1 => "Poniedziałek", 2 => 'Wtorek', 3 => "Środa", 4 => "Czwartek", 5 => "Piątek", 6 => "Sobota", 0 => "Niedziela");

    return $days[$day];
  }
  public static function getMonthName($month){
    $months = array(1 => "Styczeń",
                    2 => 'Luty',
                    3 => "Marzec",
                    4 => "Kwiecień",
                    5 => "Maj",
                    6 => "Czerwiec",
                    7 => "Lipiec",
                    8 => "Sierpień",
                    9 => "Wrzesień",
                    10 => "Październik",
                    11 => "Listopad",
                    12 => "Grudzień",
                  );

    return $months[$month];
  }
  public static function getDaysSince($date, $raw = false){
    $d = date_diff( date_create(), date_create($date))->d;
    $add = $d == 1 ? "dzień" : "dni";

    return $d.(!$raw ? " ".$add : "");
  }

  public static function validate($text, $type, $min = false, $max = false){
    $result =  true;

    if($min != false && strlen($text) < $min ) $result =  false;
    if($max != false && strlen($text) > $max ) $result =  false;

    switch ($type) {
      case 'url':
        $test = filter_var($text, FILTER_VALIDATE_URL);
        if(!$test) $result =  false;
        break;

      case 'password':
        $uppercase = preg_match('/[A-ZĘÓĄŚŁŻŹĆŃ]/', $text);
        $lowercase = preg_match('/[a-zęóąśłżźćń]/', $text);
        $number    = preg_match('/[0-9]/', $text);
        if(!$uppercase)       $result =  false;
        if(!$lowercase)       $result =  false;
        if(!$number)          $result =  false;
        if(strlen($text) < 8) $result =  false;
        break;

      case 'date':
        $date = explode("-", $text);
        $test = checkdate((int)$date[1], (int)$date[2], (int)$date[0]);
        if(!$test) $result =  false;
        break;

      case 'time':
        $test = preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/', $text);
        if(!$test) $result =  false;
        break;

      case 'email':
        $test = filter_var($text, FILTER_VALIDATE_EMAIL);
        if(!$test) $result =  false;
        break;

      case 'mobile':
        $test = preg_match('/[+48] [0-9]{3} [0-9]{3} [0-9]{3}/', $text);
        if(!$test) $result =  false;
        break;

      case 'zipcode':
        $test = preg_match('/[0-9]{2}[-]{1}[0-9]{3}/', $text);
        if(!$test) $result =  false;
        break;

      case 'city':
        $test = preg_match('/[^a-zA-ZęóąśłżźćńĘÓĄŚŁŻŹĆŃ\s\-]/', $text);
        if($test && strlen($test) > 0) $result =  false;
        break;

      case 'street':
        $test = preg_match('/[^a-zA-Z0-9ęóąśłżźćńĘÓĄŚŁŻŹĆŃ\s\-.\/]/', $text);
        if($test && strlen($test) > 0) $result =  false;
        break;

      case 'name':
        $text = str_replace("_", "", $text);
        $test = preg_match('/[^:#"\'\|,()\™0-9&!a-zA-Z–-龍王弐ęóąśłżźćńĘÓĄŚŁŻŹĆŃ\s\-.]/', $text);
        if($test && strlen($test) > 0) $result =  false;
        break;

      case 'number':
        $test = is_numeric($text);
        if(!$test) $result = array('success' => false);
        break;

      case 'float':
        $test = is_numeric($text) && $text >= 0 && $text <= 1;
        if(!$test) $result = array('success' => false);
        break;
    }


    return $result;
  }

  public static function removeFromArray($string, $array){
    $index = array_search($string,$array);
    if($index !== FALSE) unset($array[$index]);

    return $array;
  }
  public static function removeDoubleWhitespaces($text){
    return preg_replace('/\s+/', ' ', $text);
  }

  public static function inc($file, $variables = [], $global = false){
    $path = dirname(getcwd())."/views/".(!$global ? Yii::$app->controller->id."/" : "")."_partials/";
    $file = $path.$file.".php";

    $output = NULL;
    if(file_exists($file)){
      extract($variables);
      ob_start();
      include($file);
      $output = ob_get_clean();

    } else {
      $output = "<span>Missing template file: ".$file."</span>";
    }

    return $output;
  }

  public static function getTimeSince($date){
    $start_date = new \DateTime($date);
    $s = $start_date->diff(new \DateTime());

    if($s->days > 0) return "D".$s->days;
    if($s->h > 0) return "H".$s->h;
    return "M".$s->i;
  }
}
