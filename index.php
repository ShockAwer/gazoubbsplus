<?php
/*********************************************
画像掲示板plus 2.36
http://php.lemon-s.com/

ORIGINAL SCRIPT:gazou.php
http://php.s3.to/

JS LIBRARY:mootools,Slimbox
http://mootools.net/
http://www.digitalia.be/software/slimbox/
*********************************************/
//-------------設定ここから-------------

// ログファイル名
define(LOGFILE, 'imglog.log');

// 画像保存ディレクトリ(gazou.phpから見て)
define(IMG_DIR, '/img/');

// タイトル
define(TITLE, '画像掲示板plus');

// 掲示板の説明
define(INFO, 'サンプルです。');

//ホームのURL
define(HOME, 'http://php.lemon-s.com/');

// 掲示板のディレクトリのURL
define(PHP_HOME, 'http://php.lemon-s.com/test/');

// このスクリプト名
define(PHP_SELF, 'index.php');

// 投稿容量制限 KB(phpの設定により2Mまで)
define(MAX_KB, '500');

// 画像投稿サイズ幅(これ以上はwidthを縮小)
define(MAX_W, '250');

// 画像投稿サイズ高さ
define(MAX_H,  '250');

// 一ページに表示する記事数(RSSに表示する記事数も)
define(PAGE_DEF, '5');

// ログ最大行数
define(LOG_MAX, '100');

// 管理者パス(必ず変更してください)
define(ADMIN_PASS, '0000');

// 管理者がチェックしてから画像表示(する=1 しない=0)
define(CHECK, 0);

// チェック中の時の代替画像
define(SOON_ICON, 'soon.gif');

// 投稿フォームを表示する(する=1 しない=0)
define(NIKKI, 1);

// レスフォームを各記事に表示する(する=1 しない=0)
define(RES_F, 1);

// タグクラウド風ナビを表示する(する=1 しない=0)
define(TAG, 1);

// 簡易画像認証を表示する(する=1 しない=0)
define(CAP, 0);

// 簡易画像認証を漢数字入力にする(する=1 しない=0)
define(CAP_K, 0);

// IDを表示する(強制=2 する=1 しない=0)
define(DISP_ID, 2);
define(IDSEED, 'idの種');

// 画像保存絶対パス(フルパスで指定 サーバの環境に合わせてください)
$path = "/var/www/vhosts/example.com/httpdocs/test/img/";

// 閲覧禁止ホスト(追加できます)
$no_host = array('kinshi.co.jp', 'kinshi.com');

// 使用禁止ワード(追加できます)
$no_word = array('死ね', '氏ね');

// 連続する記号の禁止でアスキーアート対策(追加できます)
array_push($no_word, 'script', '；；；；；', 'ｌｌｌｌｌ', '／／／／／', '＼＼＼＼＼', '＿＿＿＿＿', '￣￣￣￣￣', '━━━━━', '●●●●●', '：：：：：', ';;;;;', ':::::', '∴∵∴', ',.,.,.,.', ': : : : :', 'ゞゞゞ', '┴┴┴');

// 直リンク禁止にする(する=1 しない=0、許可するURL以外やブックマークからアクセス禁止)
define(REFERER, 0);

// 直リンク禁止の時許可するURL(追加できます)
$no_url = array('http://php.lemon-s.com/', 'http://www.lemon-s.com/');

// 文字色
$colors = array('#000000', '#666666', '#003399', '#990000', '#669900', '#cc3399', '#ff6633', '#cccc00');

//---------設定ここまで--------------

if(phpversion()>="4.1.0"){
  extract($_REQUEST);
  extract($_COOKIE);
  $upfile_name=htmlspecialchars($_FILES["upfile"]["name"]);
  $upfile=htmlspecialchars($_FILES["upfile"]["tmp_name"]);
}
/* アクセス制限 */
$host = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
$ref = $_SERVER["HTTP_REFERER"];
foreach($no_host as $value){
  if(eregi($value,$host)){
    error("アクセスできません(ホスト)");
  }
}
if(REFERER){
  foreach($no_url as $value){
	if(eregi($value,$ref)){
      $ref_f=1;
	}
	if($ref_f == 0){
      error("アクセスできません(ホームからアクセスしなおしてください)");
	}
  }
}
/* ヘッダ */
function head(&$dat){
  $time = time();
  $dat.='<?xml version="1.0" encoding="Shift_JIS"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<title>'.TITLE.'</title>
<meta http-equiv="content-type" content="text/html; charset=Shift_JIS" />
<meta http-equiv="content-script-type" content="text/javascript" />
<meta http-equiv="content-style-type" content="text/css" />
<meta name="keywords" content="'.TITLE.'" />
<meta name="description" content="'.INFO.'" />
<link rel="alternate" type="application/rss+xml" title="RSS" href="'.PHP_SELF.'?mode=rss" />
<link rel="stylesheet" href="./images/bbs.css" type="text/css" />
<script type="text/javascript" src="./images/mootools.js"></script>
<script type="text/javascript" src="./images/slimbox.js"></script>
</head>
<body>
<h1><a id="top">'.TITLE.'</a></h1>&nbsp;
[<a href="'.HOME.'" target="_top">ホーム</a>]
[<a href="'.PHP_SELF.'?r='.$time.'">リロード</a>]
[<a href="'.PHP_SELF.'?mode=search">検索</a>]
[<a href="'.PHP_SELF.'#del">記事削除</a>]
[<a href="'.PHP_SELF.'?mode=rss">RSS</a>]
[<a href="'.PHP_SELF.'?mode=admin">管理用</a>]
';
}
/* タグクラウド風ナビ */
function tag(&$dat,$page){
  global $path;

  $dat.= "<hr size=\"1\" />&nbsp;";

  $line = file(LOGFILE);

  $oya =  array();
  $ko = array();

  // 親記事とレス記事を分ける
  foreach ($line as $value){
    list($no,$res_no) = explode(',', $value);
    // レス記事
    if($res_no){
        array_unshift($ko, $value);
    // 親記事
    }else{
        $oya[] = $value;
    }
  }
  if($oya[0] != ""){
    $oya_per = array_chunk($oya, PAGE_DEF);
    $page_all = count($oya_per);
    if($oya_per[$page][0] == "") $page = 0;

    foreach($oya_per[$page] as $value){
        list($no,$res_no,,,,$sub,$com,,
			,,,,,,,) = explode(",", $value);
        $tagsize = mt_rand(2, 6);
        $dat.= "<font size=\"$tagsize\" class=\"tag\"><a href=\"#_$no\">$sub</a></font>&nbsp;\n";
	}
  }
}
/* 投稿フォーム */
function form(&$dat,$resno,$admin=""){
  global $gazoubbs,$colors;

  if (get_magic_quotes_gpc()) $gazoubbs = stripslashes($gazoubbs);
  list($cname,$cemail,$cpass,$ccolor) = explode(",", $gazoubbs);

  $maxbyte = MAX_KB * 1024;
  if($resno){
    $find = false;
    $line = file(LOGFILE);
    for($i = 0; $i < count($line); $i++){
      list($no,$res_no,$now,$name,$email,$sub,$com,) = explode(",", $line[$i]);
      if($no == $resno){
        $find = true;
        break;
      }
    }
    if(!$find) error("該当記事がみつかりません");

    if(ereg("Re\[([0-9])\]:", $sub, $reg)){
      $reg[1]++;
      $r_sub=ereg_replace("Re\[([0-9])\]:", "Re[$reg[1]]:", $sub);
    }elseif(ereg("^Re:", $sub)){ 
      $r_sub=ereg_replace("^Re:", "Re[2]:", $sub);
    }else{
      $r_sub = "Re:$sub";
    }
    $r_com = "&gt;$com";
    $r_com = ereg_replace("<br( /)?>","\r&gt;",$r_com);
    $hidden_res = "<input type=\"hidden\" name=\"res_no\" value=\"$no\" />";
    $msg = "<h4>No. $no へのレスです</h4>";
  }
  if($admin){
    $hidden = "<input type=\"hidden\" name=\"admin\" value=\"".ADMIN_PASS."\" />";
    $msg = "<h4>タグがつかえます</h4>";
  }

  $dat.='
'.$msg.'
<h2>投稿フォーム</h2><blockquote>
<form action="'.PHP_SELF.'" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" value="regist" />
'.$hidden_res.'
'.$hidden.'
<input type="hidden" name="MAX_FILE_SIZE" value="'.$maxbyte.'" />
<table cellpadding="1" cellspacing="1">
<tr><td class="forminfo"><strong>おなまえ</strong></td>
<td><input type="text" name="name" size="28" value="'.$cname.'" /></td></tr>
<tr><td class="forminfo"><strong>Eメール</strong></td>
<td><input type="text" name="email" size="28" value="'.$cemail.'" /></td></tr>
<tr><td class="forminfo"><strong>題　　名</strong></td>
<td><input type="text" name="sub" size="35" value="'.$r_sub.'" /></td></tr>
<tr><td class="forminfo"><strong>コメント</strong></td>
<td><textarea name="com" cols="48" rows="4" wrap="soft">'.$r_com.'</textarea></td></tr>
<tr><td class="forminfo"><strong>文字色</strong></td><td>
';
  if(!$ccolor) $ccolor = 0;
  for($i=0; $i<count($colors); $i++){
  	$checked = ($i == $ccolor) ? "checked=\"checked\"" : "";
  	$dat.= "<input type=\"radio\" name=\"color\" value=\"".$i."\" ".$checked." />";
  	$dat.= "<font color=\"".$colors[$i]."\">■</font>\n";
  }
  $dat.='</td></tr>
<tr><td class="forminfo"><strong>URL</strong></td>
<td><input type="text" name="url" size="63" value="http://" /></td></tr>
<tr><td class="forminfo"><strong>添付File</strong></td>
<td><input type="file" name="upfile" size="35" /></td></tr>
<tr><td class="forminfo"><strong>削除キー</strong></td>
<td><input type="password" name="pwd" size="8" maxlength="8" value="'.$cpass.'" />
<span class="fontS">(記事の削除用 英数字で8文字以内)</span></td></tr>
';

  // 簡易画像認証
  if(CAP && !$admin){
  	session_start();

  	if(CAP_K){
      $caplist = "0123456789";
  	}else{
      $caplist = "abcdefghijklmnopqrstuvwxyz0123456789";
  	}

  	$random = "";
  	for($i = 0; $i<6; $i++){
      $random.= $caplist{mt_rand(0, strlen($caplist) - 1)};
  	}
  	$_SESSION['random'] = $random;
  	$dat.= "<tr><td class=\"forminfo\"><img src=\"./images/cap.php\" alt=\"CAPTCHA\" /></td>\n";
  	$dat.= "<td><input type=\"text\" name=\"cap\" size=\"8\" />";
  	if(CAP_K){
      $dat.= "<span class=\"fontS\">(スパム防止の為表示された数字を<strong>漢数字</strong>(〇一二三)で入力してください)</span></td></tr>";
  	}else{
      $dat.= "<span class=\"fontS\">(スパム防止の為表示された内容と同じ半角英数字(小文字)を入力してください)</span></td></tr>";
  	}
  }

$dat.='
<tr><td colspan="2">
<input type="submit" value="送信する" />&nbsp;<input type="reset" value="リセット" /><br />
<ul>
<li><span class="fontS">添付可能ファイルはGIF、JPG、PNGです。</span></li>
<li><span class="fontS">ブラウザによっては正常に添付できないことがあります。</span></li>
<li><span class="fontS">最大投稿データ量は '.MAX_KB.' KB までです。</span></li>
<li><span class="fontS">画像は横 '.MAX_W.'ピクセル、縦 '.MAX_H.'ピクセルを超えると縮小表示されます。</span></li>
<li><span class="fontS">名前の後に「#任意の文字列」を入力すると◆トリップが表示されます。</span></li>
</ul>
</td></tr></table></form></blockquote>
';
}
/* 記事部分2 */
function main2($no,$res_no,$now,$name,$email,$sub,$com,$url,$host,$pwd,$ext,$w,$h,$time,$chk,$color){
  global $path,$colors;

  // URLとメールにリンク
  if($url)   $url = "<a href=\"http://$url\" target=\"_blank\">HOME</a>";
  if($email) $name = "<a href=\"mailto:$email\">$name</a>";
  $com = auto_link($com);
  // 画像ファイル名
  $img = $path.$time.$ext;
  $src = '.'.IMG_DIR.$time.$ext;

  /* 画像表示部分 */
  // <imgタグ作成
  $imgsrc = "";
  if($ext && is_file($img)){
    $size = ceil(filesize($img) / 1024);//altにサイズ表示

    if(CHECK && $chk != 1){//未チェック
    	$imgsrc = "<a href=\"".$src."\" title=\"".$sub."\" rel=\"lightbox[img]\"><img src=\"./images/".SOON_ICON."\" hspace=\"20\" alt=\"soon\" /></a>\n";
    }elseif($w && $h){//サイズがある時
    	$imgsrc = "<a href=\"".$src."\" title=\"".$sub."\" rel=\"lightbox[img]\"><img src=\"".$src."\" border=\"0\" align=\"left\" width=\"$w\" height=\"$h\" hspace=\"20\" alt=\"".$size." KB\" /></a>\n";
    }else{//それ以外
    	$imgsrc = "<a href=\"".$src."\" title=\"".$sub."\" rel=\"lightbox[img]\"><img src=\"".$src."\" border=\"0\" align=\"left\" hspace=\"20\" alt=\"".$size." KB\" /></a>\n";
    }

    // Exif
    if(exif_imagetype($src) != IMAGETYPE_JPEG){
      $exif_dat = "<br /><span class=\"fontS\">Exif情報なし</span>\n";
    }elseif(($exif = exif_read_data($src, 'IFD0', 1)) == FALSE){
      $exif_dat = "<br /><span class=\"fontS\">Exif情報なし</span>\n";
    }else{
      //XSS
      $exif1 = htmlspecialchars($exif['IFD0']['Make']);
      $exif2 = htmlspecialchars($exif['IFD0']['Model']);
      $exif3 = htmlspecialchars($exif['EXIF']['FNumber']);
      $exif4 = htmlspecialchars($exif['EXIF']['ExposureTime']);
      $exif5 = htmlspecialchars($exif['EXIF']['ISOSpeedRatings']);
      $exif6 = htmlspecialchars($exif['EXIF']['CreatorTool']);
      
      $exif_dat = "<br /><span class=\"fontS\">Exif情報&nbsp;メーカー:".$exif1."&nbsp;モデル:".$exif2."&nbsp;F値:".$exif3."&nbsp;露出時間:".$exif4."&nbsp;ISO:".$exif5."&nbsp;ソフトウェア:".$exif6."</span>\n";
    }
  }

  $dat.= ($res_no == "") ? "\n<h3><a id=\"_$no\">$sub</a></h3>\n<table align=\"center\" cellspacing=\"0\" cellpadding=\"3\" width=\"95%\" class=\"kiji\"><tr><td>\n" : "<blockquote><a id=\"_$no\">$sub</a><br />\n";
  $dat.= "[No.$no]&nbsp;";
  $dat.= "Name <strong>$name</strong> Date $now &nbsp; $url\n";
  if(!RES_F && !$res_no) $dat.="<a href=\"".PHP_SELF."?res=$no\">レス</a>\n";
  $dat.= "$exif_dat\n";
  $dat.= "<p>$imgsrc<font color=\"".$colors[$color]."\">$com</font></p>\n";
  $dat.= ($res_no == "") ? "<br clear=\"all\" />" : "</blockquote><br clear=\"all\" />";

  return $dat;
}
/* 記事部分 */
function main(&$dat,$page){
  global $path,$gazoubbs,$colors;

  $dat.= '
<h2><a id="del">記事削除</a></h2>
<blockquote>
<form action="'.PHP_SELF.'" method="post">
<input type="hidden" name="mode" value="usrdel" />
記事No<input type="text" name="no" size="3" />
削除キー<input type="password" name="pwd" size="4" maxlength="8" />
<input type="submit" value="削除" />
</form></blockquote>
  ';

  $line = file(LOGFILE);

  $oya =  array();
  $ko = array();

  // 親記事とレス記事を分ける
  foreach ($line as $value){
    list($no, $res_no) = explode(',', $value);
    // レス記事
    if($res_no){
        array_unshift($ko, $value);
    // 親記事
    }else{
        $oya[] = $value;
    }
  }

  if($oya[0] != ""){

    $oya_per = array_chunk($oya, PAGE_DEF);
    $page_all = count($oya_per);
    if($oya_per[$page][0] == "") $page = 0;

    foreach($oya_per[$page] as $value){

        // 親配列
        list($no,$res_no,$now,$name,$email,$sub,$com,$url,$host,$pwd,$ext,$w,$h,$time,$chk,$color,) = explode(',', $value);

        // 親メイン
        $dat.= main2($no,$res_no,$now,$name,$email,$sub,$com,$url,$host,$pwd,$ext,$w,$h,$time,$chk,$color);

        foreach($ko as $value2){
			// レス配列
			list($no2,$res_no2,$now2,$name2,$email2,$sub2,$com2,$url2,$host2,$pwd2,$ext2,$w2,$h2,$time2,$chk2,$color2,) = explode(',', $value2);

			// レスメイン
			if($no == $res_no2) $dat.= main2($no2,$res_no2,$now2,$name2,$email2,$sub2,$com2,$url2,$host2,$pwd2,$ext2,$w2,$h2,$time2,$chk2,$color2);
        }


        if(RES_F){
			if(get_magic_quotes_gpc()) $gazoubbs = stripslashes($gazoubbs);
			list($cname,$cemail,$cpass,$ccolor) = explode(",", $gazoubbs);
			$dat.='
<hr size="1" />
<form action="'.PHP_SELF.'" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" value="regist" />
<input type="hidden" name="res_no" value="'.$no.'" />
<input type="hidden" name="MAX_FILE_SIZE" value="'.$maxbyte.'" />
<table cellpadding="1" cellspacing="1"><tr><td colspan="2">
<strong>おなまえ</strong>&nbsp;<input type="text" name="name" size="28" value="'.$cname.'" />
<strong>Eメール</strong>&nbsp;<input type="text" name="email" size="28" value="'.$cemail.'" />
<strong>題名</strong>&nbsp;<input type="text" name="sub" size="28" value="Re.'.$sub.'" />
<strong>URL</strong>&nbsp;<input type="text" name="url" size="28" value="http://" /></td></tr><tr><td>
<strong>コメント</strong>&nbsp;<textarea name="com" cols="40" rows="2" wrap="soft">'.$r_com.'</textarea></td><td>
';
			// 文字色
			$dat.= "<strong>添付File</strong>&nbsp;<input type=\"file\" name=\"upfile\" size=\"28\" /><br />";
			$dat.= "<strong>文字色</strong>&nbsp;<select name=\"color\">\n";
			if(!$ccolor) $ccolor = 0;
			for($i=0; $i<count($colors); $i++){
				$checked = ($i == $ccolor) ? "selected=\"selected\"" : "";
				$dat.= "<option value=\"".$i."\" style=\"color:".$colors[$i].";background:#ffffff\" ".$checked.">".$i."</option>\n";
			}
			$dat.= "</select>\n&nbsp;<strong>削除キー</strong>&nbsp;<input type=\"password\" name=\"pwd\" size=\"8\" maxlength=\"8\" value=\"".$cpass."\" />\n";

			// 簡易画像認証
			if(CAP){
				$dat.= "&nbsp;<img src=\"./images/cap.php\" alt=\"CAPTCHA\" />&nbsp;<input type=\"text\" name=\"cap\" size=\"8\" />\n";
			}
			$dat.= "&nbsp;<input type=\"submit\" value=\"返信する\" />&nbsp;<a href=\"#top\">△</a></form></td></tr></table>\n";
		}
		$dat.= "</td></tr></table>\n";
        $p++;
        clearstatcache();//ファイルのstatをクリア
    }
  }

  // 改ページ処理
  if($page_all > 1){
	$dat.= "<br /><hr size=\"1\" /><br />&nbsp;<span class=\"fontL\">";
	if($page){
		$dat.= "<a href=\"".PHP_SELF."?page=".($page-1)."\">[BACK]</a>\n";
	}
	$page_all2 = $page_all;
	$p_no=0;
	while($page_all2 > 0){
		if($page == $p_no){
	  		$dat.= "[$p_no]\n";
		}else{
	  		$dat.= "<a href=\"".PHP_SELF."?page=$p_no\">[$p_no]</a>\n";
		}
		$p_no++;
		$page_all2--;
	}
	if($page < $page_all-1){
		$dat.= "<a href=\"".PHP_SELF."?page=".($page+1)."\">[NEXT]</a>\n";
	}
	$dat.= "</span><br />\n";
  }
}
/* フッタ ※著作権表示削除不可 */
function foot(&$dat){
  $dat.='<br clear="all" /><hr size="1" />&nbsp;<span class="fontS"><!-- GazouBBS v3.5 -->
SCRIPT by : <a href="http://php.lemon-s.com/" target="_blank">画像掲示板plus</a> ( ORIGINAL SCRIPT : <a href="http://php.s3.to" target="_blank">GazouBBS</a> )
</span><br /><br />
</body></html>
';
}
/* 記事書き込み */
function regist($name,$email,$sub,$com,$url,$pwd,$upfile,$upfile_name,$cap,$res_no,$color){
  global $REQUEST_METHOD,$path,$no_word,$admin;;

  // フォーム内容をチェック
  if($_SERVER["REQUEST_METHOD"] != "POST") error("不正な投稿です(GET)"); 
  if(!$name||ereg("^( |　)*$",$name)) error("名前が書き込まれていません"); 
  if(!$com||ereg("^( |　|\t)*$",$com)) error("本文が書き込まれていません"); 
  if(!$sub||ereg("^( |　)*$",$sub))   $sub="無題"; 
  if(strlen($com) > 10000) error("本文が長すぎますっ！");
  // 禁止ワード
  if (is_array($no_word)) {
    foreach ($no_word as $fuck) {
      if (preg_match("/$fuck/", $com)) error("使用できない文字が含まれています！");
      if (preg_match("/$fuck/", $sub)) error("使用できない文字が含まれています！");
      if (preg_match("/$fuck/", $name)) error("使用できない文字が含まれています！");
    }
  }
  // 簡易画像認証チェック
  if(CAP && $admin != ADMIN_PASS){
	session_start();
	$random = $_SESSION['random'];
    if(!$cap) error("画像認証が書き込まれていません");
	if(CAP_K){
		$random = ereg_replace("0","〇",$random);
		$random = ereg_replace("1","一",$random);
		$random = ereg_replace("2","二",$random);
		$random = ereg_replace("3","三",$random);
		$random = ereg_replace("4","四",$random);
		$random = ereg_replace("5","五",$random);
		$random = ereg_replace("6","六",$random);
		$random = ereg_replace("7","七",$random);
		$random = ereg_replace("8","八",$random);
		$random = ereg_replace("9","九",$random);
		if($cap != $random) error("画像認証が違います");
	}
    elseif($cap != $random) error("画像認証が違います");
  }

  $line = file(LOGFILE);
  // 時間とホスト取得
  $tim = time();
  $host = gethostbyaddr(getenv("REMOTE_ADDR"));
  // 連続投稿チェック
  list($lastno,,,$lname,,,$lcom,,$lhost,,,,,$ltime,,) = explode(",", $line[0]);
  if(RENZOKU && $host == $lhost && $tim - $ltime < RENZOKU)
    error("連続投稿はもうしばらく時間を置いてからお願い致します");
  // No.とパスと時間とURLフォーマット
  $no = $lastno + 1;
  $c_pass = $pwd;
  $pass = ($pwd) ? substr(md5($pwd),2,8) : "*";
  $now = gmdate("Y/m/d(D) H:i",$tim+9*60*60);
  $url = ereg_replace("^http://", "", $url);

  // テキスト整形 XSS
  // $name = preg_quote($name, '');
  $name = CleanStr($name);
  $email= CleanStr($email);
  $sub  = CleanStr($sub);
  $url  = CleanStr($url);
  $com  = CleanStr($com);
  
  // 改行文字の統一
  $com = str_replace( "\r\n",  "\n", $com); 
  $com = str_replace( "\r",  "\n", $com);
  // 連続する空行を一行
  $com = ereg_replace("\n((　| )*\n){3,}","\n",$com);
  $com = nl2br($com);										//改行文字の前に<br />を代入する
  $com = str_replace("\n",  "", $com);	//\nを文字列から消す。
  // 二重投稿チェック
  if($name == $lname && $com == $lcom)
    error("二重投稿です<br /><br /><a href=$PHP_SELF>リロード</a>");
  // ログ行数オーバー
  if(count($line) >= LOG_MAX){
    for($d = count($line)-1; $d >= LOG_MAX-1; $d--){
      list($dno,,,,,,,,,,$ext,,,$dtime,,) = explode(",", $line[$d]);
      if(is_file($path.$dtime.$ext)) unlink($path.$dtime.$ext);
      $line[$d] = "";
    }
  }

  // トリップ
  $name = ereg_replace("◆","◇",$name);
  $name = ereg_replace("[\r\n]","",$name);
  $names = $name;
  $name = CleanStr($name);
  if(ereg("(#|＃)(.*)",$names,$regs)){
    $capt = $regs[2];
    $capt = strtr($capt,"&amp;", "&");
    $capt = strtr($capt,"&#44;", ",");
    $name = ereg_replace("(#|＃)(.*)","",$name);
    $salt = substr($capt."H.",1,2);
    $salt = ereg_replace("[^\.-z]",".",$salt);
    $salt = strtr($salt,":;<=>?@[\\]^_`","ABCDEFGabcdef"); 
    $name.= "◆".substr(crypt($capt,$salt),-10);
  }

  // ID
  if(DISP_ID){
    if($email&&DISP_ID==1){
      $name.= " ID:???";
    }else{
      $name.= " ID:".substr(crypt(md5($_SERVER["REMOTE_ADDR"].IDSEED.gmdate("Ymd", $time+9*60*60)),'id'),-8);
    }
  }

  // アップロード処理
  if(file_exists($upfile)){
    $dest = $path.$upfile_name;
    move_uploaded_file($upfile, $dest);
    //↑でエラーなら↓に変更
    //copy($upfile, $dest);
	chmod($dest,0644);
    if(!is_file($dest)) error("アップロードに失敗しました。<br />サーバがサポートしていない可能性があります");
    $size = @getimagesize($dest);
    if($size[2]=="") error("アップロードに失敗しました。<br />画像ファイル以外は受け付けません");
    if(filesize($dest) > (MAX_KB * 1024)) error("アップロードに失敗しました。<br />制限サイズ ".MAX_KB." KBを超えています");
    $W = $size[0];
    $H = $size[1];
    $ext = substr($upfile_name,-4);
    if ($ext == ".php" || $ext == ".php3" || $ext == ".php4" || $ext == ".html") error("アップロードに失敗しました。<br />画像ファイル以外は受け付けません");
    rename($dest,$path.$tim.$ext);
    // 画像表示縮小
    if($W > MAX_W || $H > MAX_H){
      $W2 = MAX_W / $W;
      $H2 = MAX_H / $H;

      ($W2 < $H2) ? $key = $W2 : $key = $H2;

      $W = $W * $key;
      $H = $H * $key;
    }
    $mes = "画像 $upfile_name のアップロードが成功しました<br /><br />";
  }
  $chk = (CHECK) ? 0 : 1;//未チェックは0

  //クッキー保存
  $cookvalue = implode(",", array($names,$email,$c_pass,$color));
  setcookie ("gazoubbs", $cookvalue,time()+14*24*3600);  /* 2週間で期限切れ */

  $newline = "$no,$res_no,$now,$name,$email,$sub,$com,$url,$host,$pass,$ext,$W,$H,$tim,$chk,$color,\n";

  $fp = fopen(LOGFILE, "w");
  flock($fp, 2);
  fputs($fp, $newline);
  fputs($fp, implode('', $line));
  fclose($fp);

  header("Content-type: text/html; charset=Shift_JIS");
  header("Location: ".PHP_SELF."?");
  exit;
}
/* テキスト整形 */
function CleanStr($str){
  global $admin;

  //$str = trim($str);//先頭と末尾の空白除去
  if (get_magic_quotes_gpc()) {//￥を削除
    $str = stripslashes($str);
  }
  if($admin!=ADMIN_PASS){//管理者はタグ可能
    $str = htmlspecialchars($str);//タグっ禁止
    $str = str_replace("&amp;", "&", $str);//特殊文字
  }
  return str_replace(",", "&#44;", $str);//カンマを変換
}
/* ユーザー削除 */
function usrdel($no,$pwd){
  global $path;

  if($no == "") error("削除Noが入力漏れです");

  $line = file(LOGFILE);
  $flag = FALSE;

  for($i = 0; $i<count($line); $i++){
    list($dno,$dres_no,,,,,,,,$pass,$dext,,,$dtim,,) = explode(",", $line[$i]);
    if($no == $dno){
      if(substr(md5($pwd),2,8) == $pass || ($pwd == '' && $pass == '*')){
        $flag = TRUE;
        $line[$i] = "";			//パスワードがマッチした行は空に
        $delfile = $path.$dtim.$dext;	//削除ファイル
      }
    }
    if($no == $dres_no){
        $flag = TRUE;
        $line[$i] = "";
        $delfile = $path.$dtim.$dext;
    }
  }

  if(!$flag) error("該当記事が見つからないかパスワードが間違っています");
  // ログ更新
  $fp = fopen(LOGFILE, "w");
  flock($fp, 2);
  fputs($fp, implode('', $line));
  fclose($fp);

  if(is_file($delfile)) unlink($delfile);//削除
}
/* パス認証 */
function valid($pass){
  if($pass && $pass != ADMIN_PASS) error("パスワードが違います");

  head($dat);
  echo $dat;
  echo "[<a href=\"".PHP_SELF."\">掲示板に戻る</a>]\n";
  echo "<h3>管理モード</h3>\n";
  echo "<p><form action=\"".PHP_SELF."\" method=\"post\">\n";
  // ログインフォーム
  if(!$pass){
    echo "<input type=\"radio\" name=\"admin\" value=\"del\" checked=\"checked\" />記事削除 ";
    echo "<input type=\"radio\" name=\"admin\" value=\"post\" />管理人投稿<p>";
    echo "<input type=\"hidden\" name=mode value=admin />\n";
    echo "<input type=\"password\" name=\"pass\" size=\"8\" />";
    echo "<input type=\"submit\" value=\" 認証 \" /></form>\n";
    die("</body></html>");
  }
}
/* 管理者削除 */
function admindel($delno,$chkno,$pass){
  global $path;

  if(is_array($delno)){
    $line = file(LOGFILE);
    $find = FALSE;
    for($i = 0; $i < count($line); $i++){
      list($no,$res_no,$now,$name,$email,$sub,$com,$url,
           $host,$pw,$ext,$w,$h,$tim,$chk,$color,) = explode(",",$line[$i]);
      if(in_array($no, $delno)){
        $find = TRUE;
        $line[$i] = "";
        $delfile = $path.$tim.$ext;	//削除ファイル
      }
      if(in_array($res_no, $delno)){
        $flag = TRUE;
        $line[$i] = "";
        $delfile = $path.$tim.$ext;	//削除ファイル
      }
      if($find){//ログ更新
        $fp = fopen(LOGFILE, "w");
        flock($fp, 2);
        fputs($fp, implode('', $line));
        fclose($fp);

        if(is_file($delfile)) unlink($delfile);//削除
      }
  	}
  }

  if(is_array($chkno)){
    $line = file(LOGFILE);
    $find = FALSE;
    for($i = 0; $i < count($line); $i++){
      list($no,$res_no,$now,$name,$email,$sub,$com,$url,
           $host,$pw,$ext,$w,$h,$tim,$chk,$color,) = explode(",",$line[$i]);
      if(in_array($no, $chkno)){//画像チェック$chk=1に
        $find = TRUE;
        $line[$i] = "$no,$res_no,$now,$name,$email,$sub,$com,$url,$host,$pw,$ext,$w,$h,$tim,1,$color,\n";
      }
      if($find){//ログ更新
        $fp = fopen(LOGFILE, "w");
        flock($fp, 2);
        fputs($fp, implode('', $line));
        fclose($fp);
      }
  	}
  }

  // 削除画面を表示
  echo "<input type=\"hidden\" name=\"mode\" value=\"admin\" />\n";
  echo "<input type=\"hidden\" name=\"admin\" value=\"del\" />\n";
  echo "<input type=\"hidden\" name=\"pass\" value=\"$pass\" />\n";
  echo "削除したい記事のチェックボックスにチェックを入れ、削除ボタンを押して下さい。\n";
  echo "<table border=\"1\" cellspacing=\"0\">\n";
  echo "<tr bgcolor=\"#6080f6\"><th>削除</th><th>記事No</th><th>投稿日</th><th>題名</th>";
  echo "<th>投稿者</th><th>コメント</th><th>ホスト名</th><th>添付<br />(Bytes)</th>";
  if(CHECK) echo "<th>画像<br />許可</th>";
  echo "</tr>";

  $line = file(LOGFILE);

  for($j = 0; $j < count($line); $j++){
    $img_flag = FALSE;
    list($no,$res_no,$now,$name,$email,$sub,$com,$url,
         $host,$pw,$ext,$w,$h,$time,$chk,$color,) = explode(",",$line[$j]);
    // フォーマット
    list($now,$dmy) = split("\(", $now);
    if($email) $name="<a href=\"mailto:$email\">$name</a>";
    $com = str_replace("<br />"," ",$com);
    $com = htmlspecialchars($com);
    if(strlen($com) > 40) $com = substr($com,0,38) . " ...";
    // 画像があるときはリンク
    if($ext && is_file($path.$time.$ext)){
      $img_flag = TRUE;
      $clip = "<a href=\".".IMG_DIR.$time.$ext."\" target=\"_blank\">".$time.$ext."</a>";
      $size = filesize($path.$time.$ext);
      $all += $size;			//合計計算
    }else{
      $clip = "";
      $size = 0;
    }
    $bg = ($j % 2) ? "#d6d6f6" : "#f6f6f6";//背景色

    echo "<tr bgcolor=\"$bg\"><th><input type=\"checkbox\" name=\"del[]\" value=\"$no\" /></th>";
    echo "<th>$no</th><td><span class=\"fontS\">$now</span></td><td>$sub</td>";
    echo "<td><strong>$name</strong></td><td><span class=\"fontS\">$com</span></td>";
    echo "<td>$host</td><td align=\"center\">$clip<br />($size)</td>\n";

    if(CHECK){//画像チェック
      if($img_flag && $chk == 1){
        echo "<th><font color=\"red\">OK</font></th>";
      }elseif($img_flag && $chk != 1) {
        echo "<th><input type=\"checkbox\" name=\"chk[]\" value=\"$no\" /></th>";
      }else{
        echo "<td><br /></td>";
      }
    }
    echo "</tr>\n";
  }
  if(CHECK) $msg = "or許可する";

  echo "</table><p><input type=\"submit\" value=\"削除する$msg\" />";
  echo "<input type=\"reset\" value=\"リセット\" /></form>\n";

  $all = (int)($all / 1024);
  echo "【 画像データ合計 : <strong>$all</strong> KB 】";
  die("</center></body></html>");
}
/* オートリンク */
function auto_link($proto){
  $proto = ereg_replace("(https?|ftp|news)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)","<a href=\"\\1\\2\" target=\"_blank\">\\1\\2</a>",$proto);
  return $proto;
}
/* エラー画面 */
function error($mes){
  global $upfile_name,$path;

  if(is_file($path.$upfile_name)) unlink($path.$upfile_name);

  echo "<?xml version=\"1.0\" encoding=\"Shift_JIS\"?>
        <!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
        <html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"ja\" lang=\"ja\">
        <head><META HTTP-EQUIV=\"Content-type\" CONTENT=\"text/html; charset=Shift_JIS\"><head>
        <title>".TITLE."</title><body>";
  echo "<br /><br /><br /><br />
        <center><font color=\"red\" size=\"5\"><strong>$mes</strong></font></center>
        <br /><br />";
  die("</body></html>");
}
/* 検索モード */
function search($w, $andor, $target){
  global $path;

  if(get_magic_quotes_gpc()) $w = stripslashes($w); //￥消去

  $log = file(LOGFILE);

  head($dat);
  echo $dat;
  echo "[<a href=\"".PHP_SELF."\">掲示板に戻る</a>]
<h3>検索モード</h3>
検索したい単語をスペースで区切って入力してください。<br />
<form action=\"".PHP_SELF."\" method=\"post\">
<input type=\"hidden\" name=\"mode\" value=\"search\" />
<input type=\"text\" name=\"w\" size=\"30\" value=\"".htmlspecialchars($w)."\" />
<select name=\"andor\"><option value=\"and\" selected=\"selected\">AND\n
<option value=\"or\">OR</select>
<input type=\"submit\" value=\"検索\" /><br /><br />\n";

  if(trim($w)!=""){// 前後のスペース除去
    $keys = preg_split("/(　| )+/", $w);// 複数語を配列に
    while(list(,$line) = each($log)){// ログを走査
      $find = FALSE;
      for($i = 0; $i < count($keys); $i++){
        if($keys[$i]=="") continue;
        if(stristr($line,$keys[$i])){// マッチ
          $find = TRUE;
          $line = str_replace($keys[$i],"<b style='color:green;background-color:#ffff66'>$keys[$i]</strong>",$line);
        }elseif($andor == "and"){// ANDの場合次へ
          $find = FALSE;
          break;
        }
      }
      if($find) $result[] = $line;	//マッチしたログを配列に
    }
    //結果表示
    echo "<div align=\"left\">検索結果".count($result)."件<br />";
    for($c = 0; $c < count($result); $c++){//結果展開
      list($no,$res_no,$now,$name,$email,$sub,$com,$url,
         $host,$pw,$ext,$w,$h,$time,$chk,$color,) = explode(",", $result[$c]);
      //フォーマット
      if($url)   $url = "<a href=\"http://$url\" target=\"_blank\">HOME</a>";
      if($email) $name = "<a href=\"mailto:$email\">$name</a>";
      if(LINK) $com = auto_link($com);//オートリンク
      //レスの場合レスNo追加
      $tit = ($res_no != "") ? "$no $res_no のレス" : $no;
      //結果表示
      echo "<hr size=\"1\" />[No.$tit]
<strong>$sub</strong>
Name：<strong>$name</strong> <span class=\"fontS\">Date：$now</span> $url<br />
<blockquote>$com</blockquote><br />\n";
    }
  }
  die("</form></body></html>");
}
/* RSS */
function rss(){
  $rss ='<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
 <channel>
   <title>'.TITLE.'</title>
   <link>'.HOME.'</link>
   <description>'.INFO.'</description>
   <language>ja</language>
';
  $line = file(LOGFILE);
  $st = ($page) ? $page : 0;
  for($i = $st; $i < $st+PAGE_DEF; $i++){
    if($line[$i]=="") continue;
    list($no,,$now,,,$sub,$com,,
         ,,,,,,,) = explode(",", $line[$i]);
	$now0 = preg_replace("/(\(|（).*(\)|）)/","",$now);
	$pubdate = date(r, strtotime($now0));
    $desc0 = strip_tags($com);
    $desc = htmlspecialchars($desc0);//タグっ禁止
	//$desc = mb_strimwidth($desc0,0,500,"...","Shift_JIS");	//文字列丸める
    $rss.='   <item>
    <title>'.$sub.'</title>
    <link>'.PHP_HOME.''.PHP_SELF.'#_'.$no.'</link>
    <description>'.$desc.'</description>
    <pubDate>'.$pubdate.'</pubDate>
   </item>
';
  }
  $rss.=' </channel>
</rss>
  ';
  // UTF-8に変換
  $rss = mb_convert_encoding($rss, "UTF-8", "SJIS");
  echo $rss;
}
/*-----------Main-------------*/
switch($mode){
  case 'regist':
    regist($name,$email,$sub,$com,$url,$pwd,$upfile,$upfile_name,$cap,$res_no,$color);
    break;
  case 'admin':
    valid($pass);
    if($admin=="del") admindel($del,$chk,$pass);
    if($admin=="post"){
      echo "</form>";
      form($post,$res,1);
      echo $post;
      die("</body></html>");
    }
    break;
  case 'search':
    search($w, $andor, $f);
    break;
  case 'rss':
    rss();
    break;
  case 'usrdel':
    usrdel($no,$pwd);
  default:
    head($buf);
    if(TAG) tag($buf,$page);
    if(NIKKI) form($buf,$res);
    main($buf,$page);
    foot($buf);
    echo $buf;
}
?>