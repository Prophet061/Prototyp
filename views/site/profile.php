<?php use app\models\Tools;

$this->title = 'Moje wpisy na blogu';
?>


<div class="mb-5">
  <h1 class="cl-almost-black">Moje wpisy na blogu:</h1>
</div>

<? if($posts) foreach ($posts as $p) { ?>

  <?= Tools::inc("box", ['data' => $p], true) ?>

<? } ?>
