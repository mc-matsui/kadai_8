<?php

//フォームから送られてきたか判定
if (isset($_POST['write']))
{
	$name = htmlspecialchars($_POST['name']);
	$contents = htmlspecialchars($_POST['contents']);
	$contents = nl2br($_POST['contents']);
	$contents = str_replace("\r\n", "<br>", $contents);
	$contents = str_replace("\r", "<br>", $contents);
	$contents = str_replace("\n", "<br>", $contents);
	$timestamp = time() ; //unixタイムスタンプ設定
	$time = date("Y/m/d H:i:s",$timestamp);

	//名前が空白の時は『匿名希望』表示
	if ($name=="")
	{
		$name = "匿名希望";
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

print "<br><a href = \"kadai8_1.php\">戻る</a>";


