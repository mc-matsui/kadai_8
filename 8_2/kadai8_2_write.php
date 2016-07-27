<?php

//フォームから送られてきたか判定
if (isset($_POST['write']))
{
	$name = htmlspecialchars($_POST['name']);
	$contents = htmlspecialchars($_POST['contents']);
	$mail = htmlspecialchars($_POST['mail']);
// 	$contents = nl2br($_POST['contents']);
// 	$contents = str_replace("\r\n", "<br>", $contents);
// 	$contents = str_replace("\r", "<br>", $contents);
// 	$contents = str_replace("\n", "<br>", $contents);
	$timestamp = time() ; //unixタイムスタンプ設定
	$time = date("Y/m/d H:i:s",$timestamp);	//掲示板用時間フォーマット
	$timemail = date("Y年m月d日 H時i分s秒",$timestamp); //メール送信用時間フォーマット

	//名前が空白の時は『匿名希望』表示
	if ($name=="")
	{
		$name = "匿名希望";
	}
	//メール送信(正規表現でメールアドレス判定)
	if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail))
	{
		mb_language("japanese");
		mb_internal_encoding("UTF-8");

		$to      = 'ryomatest4@gmail.com';	//受信者（※松井個人スマホGmailアドレス）
		$subject = 'PC用BBS 投稿のお知らせ';	//タイトル
		$message = $timemail . "に" . $name . "さんから書き込みがありました\n";	//本文
		$message .= "(入力内容)\n------------------------------------------\n";
		$message .= $contents . "\n\n";
		$message .= " ■BBSへのURLはこちら↓■\n";
		$message .= "http://dev3.m-craft.com/matsui/mc_kadai/kadai_8/8_2/kadai8_2.php\n";
		$headers="From:" .mb_encode_mimeheader("BBS管理局") ."<$mail>"; //ヘッダー

		if (mb_send_mail($to, $subject, $message, $headers))
		{
			print "送信成功<br>";
		}
		else
		{
			print "送信失敗<br>";
		}

		print "メール送信されました<br>";
	}
	else
	{
		print "正しいメールアドレスを入力してください";
		print "<br><a href = \"kadai8_2.php\">戻る</a>";
		exit();
	}

	//課題7_1のBBSフォーマットにしてデータ格納
	$data = "***>>" . $name . "\n";
	$data .= "date>>" . $time . "\n";
	$data .= "cont>>" . "\n";
	$data .= $contents . "\n";
	$data .= "contend>>" . "\n";

	//var_dump($data);die;

	//追記でデータ格納
	$fp = fopen('lesson.bbs', 'a');

	if ($fp)
	{
		if (flock($fp, LOCK_EX))	// 排他ロック(読み書き禁止)
		{
			if (fwrite($fp,  $data) === false)
			{
				print "ファイル書き込みに失敗しました<br>";
			}
			else
			{
				fseek($fp, 0);	// 指定オフセットまでファイルポインタ移動(データ追記では未定義)
				print "ファイルに書き込みました<br>";
			}

			flock($fp, LOCK_UN);	// 排他ロック解除
		}
		else
		{
			print "ファイルロックに失敗しました<br>";
		}
	}
	$flag = fclose($fp);
}
else
{
	print "ファイル書き込みに失敗しました<br>";
}

print "<br><a href = \"kadai8_2.php\">戻る</a>";


