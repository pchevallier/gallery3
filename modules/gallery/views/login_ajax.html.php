<?php defined("SYSPATH") or die("No direct script access.") ?>
<script type="text/javascript">
  $("#g-login-form").ready(function() {
    $("#g-password-reset").click(function() {
      $.ajax({
        url: "<?= url::site("password/reset") ?>",
        success: function(data) {
          $("#g-login").html(data);
          $("#ui-dialog-title-g-dialog").html(<?= t("Reset password")->for_js() ?>);
          $(".submit").addClass("g-button ui-state-default ui-corner-all");
          $(".submit").gallery_hover_init();
          ajaxify_login_reset_form();

          // See comment about IE7 below
          setTimeout('$("#g-name").focus()', 100);
        }
      });
    });

    // Setting the focus here doesn't work on IE7, perhaps because the field is
    // not ready yet?  So set a timeout and do it the next time we're idle
    setTimeout('$("#g-username").focus()', 100);
  });

  function ajaxify_login_reset_form() {
    $("#g-login form").ajaxForm({
      dataType: "json",
      success: function(data) {
        if (data.form) {
          $("#g-login form").replaceWith(data.form);
          ajaxify_login_reset_form();
        }
        if (data.result == "success") {
          $("#g-dialog").dialog("close");
          window.location.reload();
        }
      }
    });
  };
</script>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script src="https://apis.google.com/js/platform.js" async defer></script>
</head>
<body>
<div id="g-login">
  <ul>
    <li id="g-login-form">
      <?= $form ?>
    </li>
    <? if (identity::is_writable() && !module::get_var("gallery", "maintenance_mode")): ?>
    <li>
      <a href="#" id="g-password-reset" class="g-right g-text-small"><?= t("Forgot your password?") ?></a>
    </li>
    <? endif ?>
  </ul>
  <hr>
  gmail account: 
  <br>
  <?php
      require_once 'vendor/autoload.php';

      $client = new Google_Client();
      $client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
      $client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
      $client->setAuthConfig('var/client_secret.json');
			$authUrl = $client->createAuthUrl();
      print('<a class="login" href="');
      print($authUrl); 
      print('"><img  height="60" background=#FF0 src="');
      print(url::file("modules/gallery/images/google-login-button.png"));
      print('" /></a>')
      ?>
 
		
</div>
