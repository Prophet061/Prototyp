d = console.log;
$last_error = false;
function ajaxSend($form_holder, $additional_data = {}){
  if(event) event.preventDefault();

  d("AJAX | Rozpoczynam dla "+$form_holder);
  if(!$form_holder) return false;
  $form = $("#"+$form_holder);
  if(!$form) return false;

  $data = new FormData();
  
  $data.append("additional", $additional_data);
  $data.append("_csrf", $("meta[name='csrf-token']").attr("content"));

  $url = $($form).attr("action") ? $($form).attr("action") : location.href;
  $method = $($form).attr("method") ? $($form).attr("method") : "POST";

  $($($form).find("input, select")).each(function(i, e){
    $id = $(e).attr('id');
    switch($(e).attr("type")){
      case "checkbox":
        $data.append($id, $(e).is(":checked") ? 1 : 0);
        break;

      case "file":
        $f = $(e).get(0).files;


        $data.append($id, $(e).attr("multiple") ? $f : $f[0]);

        break;

      default:
        $data.append($id, $(e).val());
        break;
    }
  });

  $($($form).find("textarea")).each(function(i, e){
    if(typeof(tinymce) != "undefined"){
      $id = $(e).attr('id');
      $editor = tinymce.get($id)
      $data.append($id, $editor ? $editor.getContent() : false);

    } else {
      $id = $(e).attr('id');
      $data.append($id, $(e).val());

    }
  });


  console.clear();
  d("AJAX | URL "+$url);
  d("AJAX | METHOD "+$method);

  $.ajax({
    url: $url,
    data: $data,
    type: $method,
    cache: false,
    async: true,
    contentType: false,
    processData: false,
    xhr: function () {
    var myXhr = $.ajaxSettings.xhr();
    if (myXhr.upload) {
        // myXhr.upload.addEventListener('progress', that.progressHandling, false);
        d(myXhr);
    }
    return myXhr;
    },
  }).done(function(data){
    $form.find("p.error").text("");
    data = JSON.parse(data);
    d("AJAX | AJAX Data");
    d(data);

    if(data && data.close == true)    $(".modal").modal('hide');
    if(data && data.clear == true)    $("input:not([type='hidden'])").val("");
    if(data && data.reload == true)   location.reload();
    if(data && data.redirect) window.location = data.redirect;


    if(data && data.errors){
      d(data.errors);
      $(".actions>*:first-child").fadeIn();
      $.map(data.errors, function(v, k) {
        $form.find("#"+k).nextAll(".error").text(v);
      });
    }

  }).fail(function($r){
    $(".actions>*:first-child").fadeIn();
    d($r.responseText);
  });
}
