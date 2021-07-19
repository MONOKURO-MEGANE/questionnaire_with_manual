<?php
require_once('google_key.php');

// php.iniの編集ができない場合の言語とエンコード指定
mb_language("japanese");
mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");

/* 認証処理(Google_reCapthca)_(begin) */
$recaptcha = htmlspecialchars($_POST["g-recaptcha-response"],ENT_QUOTES,'UTF-8');

if(isset($recaptcha)){
	$captcha = $recaptcha;
}else{
	$captcha = "";
	echo "captchaエラー";
	exit;
}
$secretKey = "SECRET_KEY";

$resp = @file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captcha}");
$resp_result = json_decode($resp,true);

if(intval($resp_result["success"]) !== 1) {
	//認証失敗時の処理をここに書く
	echo '認証失敗';
}else{
	//認証成功時の処理をここに書く
	//ここにmailsend等の記述をする。
	echo '認証成功';
}
/* 認証処理(Google_reCapthca)_(end) */

/* SQL文(UPDATE)の構築_(begin) */
function create_sql () {
	$table_name = array('スマホゲーム', 'プロ野球', '映画', '大晦日', 'スポーツ', '箱根駅伝');
	$genre_name = array('portable_game', 'baseball', 'movie', 'television', 'sports', 'Hakone');

	$i = 0;
	foreach($genre_name as $key) {
		$user_checked = array();
		foreach($_POST["{$key}"] as $value){
			if(isset($value) === true) {
				array_push($user_checked, trim("'{$value}'"));
			}
		}

		$param = implode(',', $user_checked);

		$request_sql[$i] = "UPDATE {$table_name[$i]} SET 投票数 = 投票数 + 1 WHERE 英語名 IN ({$param});";
		$i++;

	}
	return $request_sql;
}
/* SQL文(UPDATE)の構築_(end) */

/* データベース(SQLite3)への問い合わせ（関数本体）_(begin) */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	try {
		$db = new SQLite3('第4回アンケート.db');

		$db->exec('begin');

		$sql = create_sql();

		for($i = 0; $i < count($sql); $i++) {
			$stmt = $db->prepare($sql[$i]);
			$result = $stmt->execute();
		}

		$db->exec('commit');
	} catch (Exception $e) {
		$db->exec('rollback');

		echo nl2br(htmlspecialchars('<h2>DB接続エラー</h2>', ENT_QUOTE, 'UTF-8'));
		echo $e->getTraceAsString();
	}

	$db->close();
}
/* データベース(SQLite3)への問い合わせ（関数本体）_(end) */

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
</head>
<body>
	<header></header>
	<nav></nav>
	<article>
		<section>
		<p>アンケートにご参加していただき、ありがとうございます。</p>

		</section>
	</article>
	<footer></footer>
</body>
</html>
