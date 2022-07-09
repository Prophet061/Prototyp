<?php namespace app\assets;
use yii\web\AssetBundle;

class AppAsset extends AssetBundle {
  public $basePath='@webroot';
  public $baseUrl='@web';

  public $css=[
    'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css',
    'css/fontawesome.css',
    'css/navbar.css',
    'css/scrollbar.css',
    'css/site.css',
  ];

  public $js=[
    '/js/jquery.js',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js',
    'https://cdn.tiny.cloud/1/g2oc1b2y9o1om0wy0eoyh9x14ninzz75qkr8wo66poapq5ka/tinymce/6/tinymce.min.js',
    '/js/navbar.js',
    '/js/libs.js',
  ];
}
