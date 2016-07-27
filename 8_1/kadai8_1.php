<?php

$total=0;
$count = 10; //10件まで表示用の変数
$file   = file('lesson.bbs');
$fps = fopen("lesson.bbs", "r"); //lesson.bbs読み込み

while ($lines = fgets($fps))
{
	//BBS最後の行『contend>>』を取得して1件カウント
	if (preg_match("/contend>>/",$lines))
	{
		$total++;
	}
}

//データが10件を超えた場合の判定
if ($total > $count)
{
	// ファイルの先頭に移動する。(※古い投稿から削除するため)
	fseek($fps, 0);

	$i = 0;
	while ($lines = fgets($fps))
	{
		//表示件数が10件になるまでループ
		if ($total != $count)
		{
			if (preg_match("/contend>>/",$lines))
			{
				$total--; //10件になるまでデクリメント
			}
			//『contend>>』までのBBSファイル内の行をループで削除
			unset($file[$i]);
			file_put_contents('lesson.bbs', $file);
		}
		$i++;
	}

}
fclose($fps);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>● 課題8_1, 一番古いデータを削除する</title>
<script type="text/javascript">
<!--
function wupBtn()
{
  //投稿ないようを変数に格納
  var contents = document.send.contents.value;

  //投稿内容の空チェック判定
  if (contents == "")
  {
    //投稿内容が空であれば、ボタンを押せなくする
    document.send.write.disabled=true;
  }
  else
  {
    //投稿内容が書き込まれていたら、ボタンを押せるようにする
    document.send.write.disabled=false;
  }
}
// -->
</script>
</head>
<body>
<h1>● 課題8_1, 一番古いデータを削除する</h1>
<br>
<?php
// $cnt = array();
// $cnterr = array();
// $cnt[] = $zip_code;
// $total = count($cnt);

$fp = fopen("lesson.bbs", "r"); //lesson.bbs読み込み
$number = 0;		//数字初期化
flock($fp,LOCK_EX); // 排他ロック(読み書き禁止)
//$lineでファイルの内容取得
while ($line = fgets($fp))
{
		if (preg_match("/\*\*\*>>/",$line))	//正規表現（***>>）
		{
			$number++;
			$line = str_replace("***>>", "$number,&nbsp", $line);	//確実に***>>のみ変換
			print $line;
		}
		elseif (preg_match("/date>>/",$line))	//正規表現（date>>）
		{
			$line = str_replace("date>>", "(投稿日時：&nbsp", $line);	//確実にdate>>のみ変換
			print $line . ")<br>";
		}
		elseif (preg_match("/cont>>/",$line))	//正規表現（cont>>）
		{
			print "&nbsp&nbsp";
		}
		elseif (preg_match("/contend>>/",$line))	//正規表現（contend>>）
		{
			print "<br>-------------------------------------------<br>";
		}
		else
		{
			print  $line."<br>";
		}
}

flock($fp,LOCK_UN); // ロック開放
fclose($fp);


?>
<br><br>
<!-- bbs投稿 -->
<form method="post" name="send" action="kadai7_2_write.php">
名前: <input type="text" name="name" ><br>
内容: <br><textarea name="contents" cols="30" rows="2" onchange="wupBtn()"></textarea><br>
<br>
<input type="submit" name="write" value="投稿"  disabled>
</form>
<!-- bbs投稿 -->
</body>
</html>