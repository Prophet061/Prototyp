<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

class Pagination extends Model{

  public $total = 1;
  public $records = 5;
  public $page = 1;

  public $next = true;
  public $previous = true;

  public $next_page = 1;
  public $previous_page = 1;

  public $display_from = 0;
  public $display_to = 0;

  public $reszta = 0;
  public $total_pages = 0;
  public $min_page = 0;
  public $max_page = 0;

  public $search = "";

  public $order_by = "id";
  public $order = "ASC";

  function __construct() {
      $controller = Yii::$app->controller->id;
      $custom_records = ['skin' => 500, 'items' => 500];
      if(isset($custom_records[$controller])) $this->records = $custom_records[$controller];

  }

  public function generatePagination($params = []){
    $data = Yii::$app->request->get();
    $search_pattern = isset($data['search']) ? $data['search'] : [];

    $this->search = $search_pattern;

    $this->total = $params["total"];
    $this->records = isset($data['records']) ? $data["records"] : (isset($params['records']) ? $params['records'] : $this->records);
    $this->page = isset($data['page']) ? $data['page'] : 1;

    $this->order_by = isset($data['order_by']) ? $data['order_by'] : "id";
    $this->order = isset($data['order']) ? $data['order'] : "ASC";

    $this->next_page = $this->page + 1;
    $this->previous_page = $this->page - 1;

    $this->display_from = $this->records*$this->page - $this->records + 1;
    $this->display_to = $this->records*$this->page;

    $this->total = gettype($this->total) == "array" ? count($this->total) : $this->total;

    $this->total_pages = intval($this->total/$this->records);
    if(($this->total_pages * $this->records) < $this->total) $this->total_pages = $this->total_pages +1;

    $max_to_side = 3;
    $this->min_page = 0;
    $this->max_page = 999999999;

    $add_to_start = 0;
    $add_to_end = 0;
    $this->min_page = $this->page - $max_to_side - $add_to_start > 1 ? $this->page - $max_to_side - $add_to_start : 1;
    $start_diff = $this->page - $this->min_page;
    if($start_diff < $max_to_side) $add_to_end = $max_to_side - $start_diff;

    $this->max_page = $this->page + $max_to_side + $add_to_end < $this->total_pages ? $this->page + $max_to_side + $add_to_end : $this->total_pages;
    $end_diff = $this->max_page - $this->page;
    if($end_diff < $max_to_side) $add_to_start = $max_to_side - $end_diff;

    $this->min_page = $this->page - $max_to_side - $add_to_start > 1 ? $this->page - $max_to_side - $add_to_start : 1;
    $start_diff = $this->page - $this->min_page;

    if($this->display_from > $this->total) $this->display_from = $this->total;
    if($this->display_to > $this->total) $this->display_to = $this->total;

    if($this->display_to >= $this->total) $this->next = false;
    if($this->display_from == 1) $this->previous = false;

    return $this;
  }

  public function displayPagination(){
    $controller = Yii::$app->controller->id;
    $action = Yii::$app->controller->action->id;

    $page_params = [];
    $page_params[] = $controller."/".$action;
    if($_GET) foreach ($_GET as $k => $v) $page_params[$k] = $v;


    $pagination = '<div class="custom-pagination d-flex justify-content-between">';



        $page_params['page'] = $this->previous_page;
        $pagination.= '<a title="Poprzednia strona" class="pager" href="'.($this->previous ? Url::to($page_params) : '#').'">';
          $pagination.='<i class="fas fa-chevron-left"><span class="d-none">Poprzednia strona</span></i>';
        $pagination.='</a>';

        $pagination.='<span class="pager-digits">';
          for ($i= $this->min_page; $i < $this->page; $i++) {

            $page_params['page'] = $i;
            $pagination.="<a href='".Url::to($page_params)."' class='pagi-tile'>".$i."</a>";
          }

          $pagination.="<a href='#' class='pagi-tile active'>".$this->page."</a>";


          for ($i= $this->page+1; $i <= $this->max_page; $i++) {

            $page_params['page'] = $i;
            $pagination.="<a title='Przejdź do strony #".$i."' href='".Url::to($page_params)."' class='pagi-tile'>".$i."</a>";
          }

        $pagination.='</span>';

        $page_params['page'] = $this->next_page;
        $pagination.= '<a title="Następna strona" class="pager" href="'.($this->next ? Url::to($page_params) : '#').'">';
          $pagination.='<i class="fas fa-chevron-right"><span class="d-none">Następna strona</span></i>';
        $pagination.='</a>';

    $pagination.= '</div>';

    echo $pagination;
  }
  public function displayRecordSelector(){
    $options = array(5, 10, 20, 50);

    $selector = '<select class="custom-select" id="records" data-no-search="true">';
      foreach ($options as $key => $value) {
        $selector .= '<option '.($this->records == $value ? 'selected ' : '').'value="'.$value.'">'.$value.'</option>';
      }
    $selector .= '</select>';

    echo $selector;
  }
  public function displayRecordCounter(){
    $from = $this->display_from;
    $to = $this->display_to;
    $total = $this->total;

    if($to > $total) $to = $total;
    $text = "<p>Wyświetlam <b>".$from."</b> - <b>".$to."</b> z <b>".$total."</b></p>";

    echo $text;
  }
}
