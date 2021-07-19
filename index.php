<?php
require_once('google_key.php');
?>

<!DOCTYPE html>
<html>
<head>
	<title>アンケート試作(PHP標準API)</title>
	<meta name="robots" content="noarchive, nofollow, noindex">
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/clearfix.css">
	<link rel="stylesheet" href="css/index.css">
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src='https://www.google.com/recaptcha/api.js?hl=ja' async defer></script>
</head>
<body>
	<header></header>
	<nav></nav>
	<article>
		<section>
		<form id="vertical_radio" method="POST" action="result.php">
			<ul class="radio_group">
				<li class="list"><input class="item" type="radio" name="radio[]" value="fruits" checked>果物</li>
				<li class="list"><input class="item" type="radio" name="radio[]" value="vegetable">野菜</li>
				<li class="list"><input class="item" type="radio" name="radio[]" value="meat">肉</li>
				<li class="list"><input class="item" type="radio" name="radio[]" value="fis">魚</li>
			</ul>
			<!-- "google" => "reCAPTCHA" -->
			<div class="security"><div class="g-recaptcha" data-callback="clearcall" data-sitekey="<?php echo "SITE_KEY" ?>"></div></div>
			<script type="text/javascript">
			function clearcall(code) {
				if(code !== ""){
					$(':submit[name=button]').removeAttr("disabled");
				}
			}
			</script>
			<div class="buttons">
				<input class="send_button" type="submit" name="button" value="送信" disabled>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input class="cancel_button" type="reset" name="" value="キャンセル">
			</div>
		</form>
		</section>
	</article>
	<footer></footer>
</body>
</html>