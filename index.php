<?php
/*********************************************
�摜�f����plus 2.36
http://php.lemon-s.com/

ORIGINAL SCRIPT:gazou.php
http://php.s3.to/

JS LIBRARY:mootools,Slimbox
http://mootools.net/
http://www.digitalia.be/software/slimbox/
*********************************************/
//-------------�ݒ肱������-------------

// ���O�t�@�C����
define(LOGFILE, 'imglog.log');

// �摜�ۑ��f�B���N�g��(gazou.php���猩��)
define(IMG_DIR, '/img/');

// �^�C�g��
define(TITLE, '�摜�f����plus');

// �f���̐���
define(INFO, '�T���v���ł��B');

//�z�[����URL
define(HOME, 'http://php.lemon-s.com/');

// �f���̃f�B���N�g����URL
define(PHP_HOME, 'http://php.lemon-s.com/test/');

// ���̃X�N���v�g��
define(PHP_SELF, 'index.php');

// ���e�e�ʐ��� KB(php�̐ݒ�ɂ��2M�܂�)
define(MAX_KB, '500');

// �摜���e�T�C�Y��(����ȏ��width���k��)
define(MAX_W, '250');

// �摜���e�T�C�Y����
define(MAX_H,  '250');

// ��y�[�W�ɕ\������L����(RSS�ɕ\������L������)
define(PAGE_DEF, '5');

// ���O�ő�s��
define(LOG_MAX, '100');

// �Ǘ��҃p�X(�K���ύX���Ă�������)
define(ADMIN_PASS, '0000');

// �Ǘ��҂��`�F�b�N���Ă���摜�\��(����=1 ���Ȃ�=0)
define(CHECK, 0);

// �`�F�b�N���̎��̑�։摜
define(SOON_ICON, 'soon.gif');

// ���e�t�H�[����\������(����=1 ���Ȃ�=0)
define(NIKKI, 1);

// ���X�t�H�[�����e�L���ɕ\������(����=1 ���Ȃ�=0)
define(RES_F, 1);

// �^�O�N���E�h���i�r��\������(����=1 ���Ȃ�=0)
define(TAG, 1);

// �ȈՉ摜�F�؂�\������(����=1 ���Ȃ�=0)
define(CAP, 0);

// �ȈՉ摜�F�؂����������͂ɂ���(����=1 ���Ȃ�=0)
define(CAP_K, 0);

// ID��\������(����=2 ����=1 ���Ȃ�=0)
define(DISP_ID, 2);
define(IDSEED, 'id�̎�');

// �摜�ۑ���΃p�X(�t���p�X�Ŏw�� �T�[�o�̊��ɍ��킹�Ă�������)
$path = "/var/www/vhosts/example.com/httpdocs/test/img/";

// �{���֎~�z�X�g(�ǉ��ł��܂�)
$no_host = array('kinshi.co.jp', 'kinshi.com');

// �g�p�֎~���[�h(�ǉ��ł��܂�)
$no_word = array('����', '����');

// �A������L���̋֎~�ŃA�X�L�[�A�[�g�΍�(�ǉ��ł��܂�)
array_push($no_word, 'script', '�G�G�G�G�G', '����������', '�^�^�^�^�^', '�_�_�_�_�_', '�Q�Q�Q�Q�Q', '�P�P�P�P�P', '����������', '����������', '�F�F�F�F�F', ';;;;;', ':::::', '���恈', ',.,.,.,.', ': : : : :', '�U�U�U', '������');

// �������N�֎~�ɂ���(����=1 ���Ȃ�=0�A������URL�ȊO��u�b�N�}�[�N����A�N�Z�X�֎~)
define(REFERER, 0);

// �������N�֎~�̎�������URL(�ǉ��ł��܂�)
$no_url = array('http://php.lemon-s.com/', 'http://www.lemon-s.com/');

// �����F
$colors = array('#000000', '#666666', '#003399', '#990000', '#669900', '#cc3399', '#ff6633', '#cccc00');

//---------�ݒ肱���܂�--------------

if(phpversion()>="4.1.0"){
  extract($_REQUEST);
  extract($_COOKIE);
  $upfile_name=htmlspecialchars($_FILES["upfile"]["name"]);
  $upfile=htmlspecialchars($_FILES["upfile"]["tmp_name"]);
}
/* �A�N�Z�X���� */
$host = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
$ref = $_SERVER["HTTP_REFERER"];
foreach($no_host as $value){
  if(eregi($value,$host)){
    error("�A�N�Z�X�ł��܂���(�z�X�g)");
  }
}
if(REFERER){
  foreach($no_url as $value){
	if(eregi($value,$ref)){
      $ref_f=1;
	}
	if($ref_f == 0){
      error("�A�N�Z�X�ł��܂���(�z�[������A�N�Z�X���Ȃ����Ă�������)");
	}
  }
}
/* �w�b�_ */
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
[<a href="'.HOME.'" target="_top">�z�[��</a>]
[<a href="'.PHP_SELF.'?r='.$time.'">�����[�h</a>]
[<a href="'.PHP_SELF.'?mode=search">����</a>]
[<a href="'.PHP_SELF.'#del">�L���폜</a>]
[<a href="'.PHP_SELF.'?mode=rss">RSS</a>]
[<a href="'.PHP_SELF.'?mode=admin">�Ǘ��p</a>]
';
}
/* �^�O�N���E�h���i�r */
function tag(&$dat,$page){
  global $path;

  $dat.= "<hr size=\"1\" />&nbsp;";

  $line = file(LOGFILE);

  $oya =  array();
  $ko = array();

  // �e�L���ƃ��X�L���𕪂���
  foreach ($line as $value){
    list($no,$res_no) = explode(',', $value);
    // ���X�L��
    if($res_no){
        array_unshift($ko, $value);
    // �e�L��
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
/* ���e�t�H�[�� */
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
    if(!$find) error("�Y���L�����݂���܂���");

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
    $msg = "<h4>No. $no �ւ̃��X�ł�</h4>";
  }
  if($admin){
    $hidden = "<input type=\"hidden\" name=\"admin\" value=\"".ADMIN_PASS."\" />";
    $msg = "<h4>�^�O�������܂�</h4>";
  }

  $dat.='
'.$msg.'
<h2>���e�t�H�[��</h2><blockquote>
<form action="'.PHP_SELF.'" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" value="regist" />
'.$hidden_res.'
'.$hidden.'
<input type="hidden" name="MAX_FILE_SIZE" value="'.$maxbyte.'" />
<table cellpadding="1" cellspacing="1">
<tr><td class="forminfo"><strong>���Ȃ܂�</strong></td>
<td><input type="text" name="name" size="28" value="'.$cname.'" /></td></tr>
<tr><td class="forminfo"><strong>E���[��</strong></td>
<td><input type="text" name="email" size="28" value="'.$cemail.'" /></td></tr>
<tr><td class="forminfo"><strong>��@�@��</strong></td>
<td><input type="text" name="sub" size="35" value="'.$r_sub.'" /></td></tr>
<tr><td class="forminfo"><strong>�R�����g</strong></td>
<td><textarea name="com" cols="48" rows="4" wrap="soft">'.$r_com.'</textarea></td></tr>
<tr><td class="forminfo"><strong>�����F</strong></td><td>
';
  if(!$ccolor) $ccolor = 0;
  for($i=0; $i<count($colors); $i++){
  	$checked = ($i == $ccolor) ? "checked=\"checked\"" : "";
  	$dat.= "<input type=\"radio\" name=\"color\" value=\"".$i."\" ".$checked." />";
  	$dat.= "<font color=\"".$colors[$i]."\">��</font>\n";
  }
  $dat.='</td></tr>
<tr><td class="forminfo"><strong>URL</strong></td>
<td><input type="text" name="url" size="63" value="http://" /></td></tr>
<tr><td class="forminfo"><strong>�Y�tFile</strong></td>
<td><input type="file" name="upfile" size="35" /></td></tr>
<tr><td class="forminfo"><strong>�폜�L�[</strong></td>
<td><input type="password" name="pwd" size="8" maxlength="8" value="'.$cpass.'" />
<span class="fontS">(�L���̍폜�p �p������8�����ȓ�)</span></td></tr>
';

  // �ȈՉ摜�F��
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
      $dat.= "<span class=\"fontS\">(�X�p���h�~�̈ו\�����ꂽ������<strong>������</strong>(�Z���O)�œ��͂��Ă�������)</span></td></tr>";
  	}else{
      $dat.= "<span class=\"fontS\">(�X�p���h�~�̈ו\�����ꂽ���e�Ɠ������p�p����(������)����͂��Ă�������)</span></td></tr>";
  	}
  }

$dat.='
<tr><td colspan="2">
<input type="submit" value="���M����" />&nbsp;<input type="reset" value="���Z�b�g" /><br />
<ul>
<li><span class="fontS">�Y�t�\�t�@�C����GIF�AJPG�APNG�ł��B</span></li>
<li><span class="fontS">�u���E�U�ɂ���Ă͐���ɓY�t�ł��Ȃ����Ƃ�����܂��B</span></li>
<li><span class="fontS">�ő哊�e�f�[�^�ʂ� '.MAX_KB.' KB �܂łł��B</span></li>
<li><span class="fontS">�摜�͉� '.MAX_W.'�s�N�Z���A�c '.MAX_H.'�s�N�Z���𒴂���Ək���\������܂��B</span></li>
<li><span class="fontS">���O�̌�Ɂu#�C�ӂ̕�����v����͂���Ɓ��g���b�v���\������܂��B</span></li>
</ul>
</td></tr></table></form></blockquote>
';
}
/* �L������2 */
function main2($no,$res_no,$now,$name,$email,$sub,$com,$url,$host,$pwd,$ext,$w,$h,$time,$chk,$color){
  global $path,$colors;

  // URL�ƃ��[���Ƀ����N
  if($url)   $url = "<a href=\"http://$url\" target=\"_blank\">HOME</a>";
  if($email) $name = "<a href=\"mailto:$email\">$name</a>";
  $com = auto_link($com);
  // �摜�t�@�C����
  $img = $path.$time.$ext;
  $src = '.'.IMG_DIR.$time.$ext;

  /* �摜�\������ */
  // <img�^�O�쐬
  $imgsrc = "";
  if($ext && is_file($img)){
    $size = ceil(filesize($img) / 1024);//alt�ɃT�C�Y�\��

    if(CHECK && $chk != 1){//���`�F�b�N
    	$imgsrc = "<a href=\"".$src."\" title=\"".$sub."\" rel=\"lightbox[img]\"><img src=\"./images/".SOON_ICON."\" hspace=\"20\" alt=\"soon\" /></a>\n";
    }elseif($w && $h){//�T�C�Y�����鎞
    	$imgsrc = "<a href=\"".$src."\" title=\"".$sub."\" rel=\"lightbox[img]\"><img src=\"".$src."\" border=\"0\" align=\"left\" width=\"$w\" height=\"$h\" hspace=\"20\" alt=\"".$size." KB\" /></a>\n";
    }else{//����ȊO
    	$imgsrc = "<a href=\"".$src."\" title=\"".$sub."\" rel=\"lightbox[img]\"><img src=\"".$src."\" border=\"0\" align=\"left\" hspace=\"20\" alt=\"".$size." KB\" /></a>\n";
    }

    // Exif
    if(exif_imagetype($src) != IMAGETYPE_JPEG){
      $exif_dat = "<br /><span class=\"fontS\">Exif���Ȃ�</span>\n";
    }elseif(($exif = exif_read_data($src, 'IFD0', 1)) == FALSE){
      $exif_dat = "<br /><span class=\"fontS\">Exif���Ȃ�</span>\n";
    }else{
      //XSS
      $exif1 = htmlspecialchars($exif['IFD0']['Make']);
      $exif2 = htmlspecialchars($exif['IFD0']['Model']);
      $exif3 = htmlspecialchars($exif['EXIF']['FNumber']);
      $exif4 = htmlspecialchars($exif['EXIF']['ExposureTime']);
      $exif5 = htmlspecialchars($exif['EXIF']['ISOSpeedRatings']);
      $exif6 = htmlspecialchars($exif['EXIF']['CreatorTool']);
      
      $exif_dat = "<br /><span class=\"fontS\">Exif���&nbsp;���[�J�[:".$exif1."&nbsp;���f��:".$exif2."&nbsp;F�l:".$exif3."&nbsp;�I�o����:".$exif4."&nbsp;ISO:".$exif5."&nbsp;�\�t�g�E�F�A:".$exif6."</span>\n";
    }
  }

  $dat.= ($res_no == "") ? "\n<h3><a id=\"_$no\">$sub</a></h3>\n<table align=\"center\" cellspacing=\"0\" cellpadding=\"3\" width=\"95%\" class=\"kiji\"><tr><td>\n" : "<blockquote><a id=\"_$no\">$sub</a><br />\n";
  $dat.= "[No.$no]&nbsp;";
  $dat.= "Name <strong>$name</strong> Date $now &nbsp; $url\n";
  if(!RES_F && !$res_no) $dat.="<a href=\"".PHP_SELF."?res=$no\">���X</a>\n";
  $dat.= "$exif_dat\n";
  $dat.= "<p>$imgsrc<font color=\"".$colors[$color]."\">$com</font></p>\n";
  $dat.= ($res_no == "") ? "<br clear=\"all\" />" : "</blockquote><br clear=\"all\" />";

  return $dat;
}
/* �L������ */
function main(&$dat,$page){
  global $path,$gazoubbs,$colors;

  $dat.= '
<h2><a id="del">�L���폜</a></h2>
<blockquote>
<form action="'.PHP_SELF.'" method="post">
<input type="hidden" name="mode" value="usrdel" />
�L��No<input type="text" name="no" size="3" />
�폜�L�[<input type="password" name="pwd" size="4" maxlength="8" />
<input type="submit" value="�폜" />
</form></blockquote>
  ';

  $line = file(LOGFILE);

  $oya =  array();
  $ko = array();

  // �e�L���ƃ��X�L���𕪂���
  foreach ($line as $value){
    list($no, $res_no) = explode(',', $value);
    // ���X�L��
    if($res_no){
        array_unshift($ko, $value);
    // �e�L��
    }else{
        $oya[] = $value;
    }
  }

  if($oya[0] != ""){

    $oya_per = array_chunk($oya, PAGE_DEF);
    $page_all = count($oya_per);
    if($oya_per[$page][0] == "") $page = 0;

    foreach($oya_per[$page] as $value){

        // �e�z��
        list($no,$res_no,$now,$name,$email,$sub,$com,$url,$host,$pwd,$ext,$w,$h,$time,$chk,$color,) = explode(',', $value);

        // �e���C��
        $dat.= main2($no,$res_no,$now,$name,$email,$sub,$com,$url,$host,$pwd,$ext,$w,$h,$time,$chk,$color);

        foreach($ko as $value2){
			// ���X�z��
			list($no2,$res_no2,$now2,$name2,$email2,$sub2,$com2,$url2,$host2,$pwd2,$ext2,$w2,$h2,$time2,$chk2,$color2,) = explode(',', $value2);

			// ���X���C��
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
<strong>���Ȃ܂�</strong>&nbsp;<input type="text" name="name" size="28" value="'.$cname.'" />
<strong>E���[��</strong>&nbsp;<input type="text" name="email" size="28" value="'.$cemail.'" />
<strong>�薼</strong>&nbsp;<input type="text" name="sub" size="28" value="Re.'.$sub.'" />
<strong>URL</strong>&nbsp;<input type="text" name="url" size="28" value="http://" /></td></tr><tr><td>
<strong>�R�����g</strong>&nbsp;<textarea name="com" cols="40" rows="2" wrap="soft">'.$r_com.'</textarea></td><td>
';
			// �����F
			$dat.= "<strong>�Y�tFile</strong>&nbsp;<input type=\"file\" name=\"upfile\" size=\"28\" /><br />";
			$dat.= "<strong>�����F</strong>&nbsp;<select name=\"color\">\n";
			if(!$ccolor) $ccolor = 0;
			for($i=0; $i<count($colors); $i++){
				$checked = ($i == $ccolor) ? "selected=\"selected\"" : "";
				$dat.= "<option value=\"".$i."\" style=\"color:".$colors[$i].";background:#ffffff\" ".$checked.">".$i."</option>\n";
			}
			$dat.= "</select>\n&nbsp;<strong>�폜�L�[</strong>&nbsp;<input type=\"password\" name=\"pwd\" size=\"8\" maxlength=\"8\" value=\"".$cpass."\" />\n";

			// �ȈՉ摜�F��
			if(CAP){
				$dat.= "&nbsp;<img src=\"./images/cap.php\" alt=\"CAPTCHA\" />&nbsp;<input type=\"text\" name=\"cap\" size=\"8\" />\n";
			}
			$dat.= "&nbsp;<input type=\"submit\" value=\"�ԐM����\" />&nbsp;<a href=\"#top\">��</a></form></td></tr></table>\n";
		}
		$dat.= "</td></tr></table>\n";
        $p++;
        clearstatcache();//�t�@�C����stat���N���A
    }
  }

  // ���y�[�W����
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
/* �t�b�^ �����쌠�\���폜�s�� */
function foot(&$dat){
  $dat.='<br clear="all" /><hr size="1" />&nbsp;<span class="fontS"><!-- GazouBBS v3.5 -->
SCRIPT by : <a href="http://php.lemon-s.com/" target="_blank">�摜�f����plus</a> ( ORIGINAL SCRIPT : <a href="http://php.s3.to" target="_blank">GazouBBS</a> )
</span><br /><br />
</body></html>
';
}
/* �L���������� */
function regist($name,$email,$sub,$com,$url,$pwd,$upfile,$upfile_name,$cap,$res_no,$color){
  global $REQUEST_METHOD,$path,$no_word,$admin;;

  // �t�H�[�����e���`�F�b�N
  if($_SERVER["REQUEST_METHOD"] != "POST") error("�s���ȓ��e�ł�(GET)"); 
  if(!$name||ereg("^( |�@)*$",$name)) error("���O���������܂�Ă��܂���"); 
  if(!$com||ereg("^( |�@|\t)*$",$com)) error("�{�����������܂�Ă��܂���"); 
  if(!$sub||ereg("^( |�@)*$",$sub))   $sub="����"; 
  if(strlen($com) > 10000) error("�{�����������܂����I");
  // �֎~���[�h
  if (is_array($no_word)) {
    foreach ($no_word as $fuck) {
      if (preg_match("/$fuck/", $com)) error("�g�p�ł��Ȃ��������܂܂�Ă��܂��I");
      if (preg_match("/$fuck/", $sub)) error("�g�p�ł��Ȃ��������܂܂�Ă��܂��I");
      if (preg_match("/$fuck/", $name)) error("�g�p�ł��Ȃ��������܂܂�Ă��܂��I");
    }
  }
  // �ȈՉ摜�F�؃`�F�b�N
  if(CAP && $admin != ADMIN_PASS){
	session_start();
	$random = $_SESSION['random'];
    if(!$cap) error("�摜�F�؂��������܂�Ă��܂���");
	if(CAP_K){
		$random = ereg_replace("0","�Z",$random);
		$random = ereg_replace("1","��",$random);
		$random = ereg_replace("2","��",$random);
		$random = ereg_replace("3","�O",$random);
		$random = ereg_replace("4","�l",$random);
		$random = ereg_replace("5","��",$random);
		$random = ereg_replace("6","�Z",$random);
		$random = ereg_replace("7","��",$random);
		$random = ereg_replace("8","��",$random);
		$random = ereg_replace("9","��",$random);
		if($cap != $random) error("�摜�F�؂��Ⴂ�܂�");
	}
    elseif($cap != $random) error("�摜�F�؂��Ⴂ�܂�");
  }

  $line = file(LOGFILE);
  // ���Ԃƃz�X�g�擾
  $tim = time();
  $host = gethostbyaddr(getenv("REMOTE_ADDR"));
  // �A�����e�`�F�b�N
  list($lastno,,,$lname,,,$lcom,,$lhost,,,,,$ltime,,) = explode(",", $line[0]);
  if(RENZOKU && $host == $lhost && $tim - $ltime < RENZOKU)
    error("�A�����e�͂������΂炭���Ԃ�u���Ă��炨�肢�v���܂�");
  // No.�ƃp�X�Ǝ��Ԃ�URL�t�H�[�}�b�g
  $no = $lastno + 1;
  $c_pass = $pwd;
  $pass = ($pwd) ? substr(md5($pwd),2,8) : "*";
  $now = gmdate("Y/m/d(D) H:i",$tim+9*60*60);
  $url = ereg_replace("^http://", "", $url);

  // �e�L�X�g���` XSS
  // $name = preg_quote($name, '');
  $name = CleanStr($name);
  $email= CleanStr($email);
  $sub  = CleanStr($sub);
  $url  = CleanStr($url);
  $com  = CleanStr($com);
  
  // ���s�����̓���
  $com = str_replace( "\r\n",  "\n", $com); 
  $com = str_replace( "\r",  "\n", $com);
  // �A�������s����s
  $com = ereg_replace("\n((�@| )*\n){3,}","\n",$com);
  $com = nl2br($com);										//���s�����̑O��<br />��������
  $com = str_replace("\n",  "", $com);	//\n�𕶎��񂩂�����B
  // ��d���e�`�F�b�N
  if($name == $lname && $com == $lcom)
    error("��d���e�ł�<br /><br /><a href=$PHP_SELF>�����[�h</a>");
  // ���O�s���I�[�o�[
  if(count($line) >= LOG_MAX){
    for($d = count($line)-1; $d >= LOG_MAX-1; $d--){
      list($dno,,,,,,,,,,$ext,,,$dtime,,) = explode(",", $line[$d]);
      if(is_file($path.$dtime.$ext)) unlink($path.$dtime.$ext);
      $line[$d] = "";
    }
  }

  // �g���b�v
  $name = ereg_replace("��","��",$name);
  $name = ereg_replace("[\r\n]","",$name);
  $names = $name;
  $name = CleanStr($name);
  if(ereg("(#|��)(.*)",$names,$regs)){
    $capt = $regs[2];
    $capt = strtr($capt,"&amp;", "&");
    $capt = strtr($capt,"&#44;", ",");
    $name = ereg_replace("(#|��)(.*)","",$name);
    $salt = substr($capt."H.",1,2);
    $salt = ereg_replace("[^\.-z]",".",$salt);
    $salt = strtr($salt,":;<=>?@[\\]^_`","ABCDEFGabcdef"); 
    $name.= "��".substr(crypt($capt,$salt),-10);
  }

  // ID
  if(DISP_ID){
    if($email&&DISP_ID==1){
      $name.= " ID:???";
    }else{
      $name.= " ID:".substr(crypt(md5($_SERVER["REMOTE_ADDR"].IDSEED.gmdate("Ymd", $time+9*60*60)),'id'),-8);
    }
  }

  // �A�b�v���[�h����
  if(file_exists($upfile)){
    $dest = $path.$upfile_name;
    move_uploaded_file($upfile, $dest);
    //���ŃG���[�Ȃ火�ɕύX
    //copy($upfile, $dest);
	chmod($dest,0644);
    if(!is_file($dest)) error("�A�b�v���[�h�Ɏ��s���܂����B<br />�T�[�o���T�|�[�g���Ă��Ȃ��\��������܂�");
    $size = @getimagesize($dest);
    if($size[2]=="") error("�A�b�v���[�h�Ɏ��s���܂����B<br />�摜�t�@�C���ȊO�͎󂯕t���܂���");
    if(filesize($dest) > (MAX_KB * 1024)) error("�A�b�v���[�h�Ɏ��s���܂����B<br />�����T�C�Y ".MAX_KB." KB�𒴂��Ă��܂�");
    $W = $size[0];
    $H = $size[1];
    $ext = substr($upfile_name,-4);
    if ($ext == ".php" || $ext == ".php3" || $ext == ".php4" || $ext == ".html") error("�A�b�v���[�h�Ɏ��s���܂����B<br />�摜�t�@�C���ȊO�͎󂯕t���܂���");
    rename($dest,$path.$tim.$ext);
    // �摜�\���k��
    if($W > MAX_W || $H > MAX_H){
      $W2 = MAX_W / $W;
      $H2 = MAX_H / $H;

      ($W2 < $H2) ? $key = $W2 : $key = $H2;

      $W = $W * $key;
      $H = $H * $key;
    }
    $mes = "�摜 $upfile_name �̃A�b�v���[�h���������܂���<br /><br />";
  }
  $chk = (CHECK) ? 0 : 1;//���`�F�b�N��0

  //�N�b�L�[�ۑ�
  $cookvalue = implode(",", array($names,$email,$c_pass,$color));
  setcookie ("gazoubbs", $cookvalue,time()+14*24*3600);  /* 2�T�ԂŊ����؂� */

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
/* �e�L�X�g���` */
function CleanStr($str){
  global $admin;

  //$str = trim($str);//�擪�Ɩ����̋󔒏���
  if (get_magic_quotes_gpc()) {//�����폜
    $str = stripslashes($str);
  }
  if($admin!=ADMIN_PASS){//�Ǘ��҂̓^�O�\
    $str = htmlspecialchars($str);//�^�O���֎~
    $str = str_replace("&amp;", "&", $str);//���ꕶ��
  }
  return str_replace(",", "&#44;", $str);//�J���}��ϊ�
}
/* ���[�U�[�폜 */
function usrdel($no,$pwd){
  global $path;

  if($no == "") error("�폜No�����͘R��ł�");

  $line = file(LOGFILE);
  $flag = FALSE;

  for($i = 0; $i<count($line); $i++){
    list($dno,$dres_no,,,,,,,,$pass,$dext,,,$dtim,,) = explode(",", $line[$i]);
    if($no == $dno){
      if(substr(md5($pwd),2,8) == $pass || ($pwd == '' && $pass == '*')){
        $flag = TRUE;
        $line[$i] = "";			//�p�X���[�h���}�b�`�����s�͋��
        $delfile = $path.$dtim.$dext;	//�폜�t�@�C��
      }
    }
    if($no == $dres_no){
        $flag = TRUE;
        $line[$i] = "";
        $delfile = $path.$dtim.$dext;
    }
  }

  if(!$flag) error("�Y���L����������Ȃ����p�X���[�h���Ԉ���Ă��܂�");
  // ���O�X�V
  $fp = fopen(LOGFILE, "w");
  flock($fp, 2);
  fputs($fp, implode('', $line));
  fclose($fp);

  if(is_file($delfile)) unlink($delfile);//�폜
}
/* �p�X�F�� */
function valid($pass){
  if($pass && $pass != ADMIN_PASS) error("�p�X���[�h���Ⴂ�܂�");

  head($dat);
  echo $dat;
  echo "[<a href=\"".PHP_SELF."\">�f���ɖ߂�</a>]\n";
  echo "<h3>�Ǘ����[�h</h3>\n";
  echo "<p><form action=\"".PHP_SELF."\" method=\"post\">\n";
  // ���O�C���t�H�[��
  if(!$pass){
    echo "<input type=\"radio\" name=\"admin\" value=\"del\" checked=\"checked\" />�L���폜 ";
    echo "<input type=\"radio\" name=\"admin\" value=\"post\" />�Ǘ��l���e<p>";
    echo "<input type=\"hidden\" name=mode value=admin />\n";
    echo "<input type=\"password\" name=\"pass\" size=\"8\" />";
    echo "<input type=\"submit\" value=\" �F�� \" /></form>\n";
    die("</body></html>");
  }
}
/* �Ǘ��ҍ폜 */
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
        $delfile = $path.$tim.$ext;	//�폜�t�@�C��
      }
      if(in_array($res_no, $delno)){
        $flag = TRUE;
        $line[$i] = "";
        $delfile = $path.$tim.$ext;	//�폜�t�@�C��
      }
      if($find){//���O�X�V
        $fp = fopen(LOGFILE, "w");
        flock($fp, 2);
        fputs($fp, implode('', $line));
        fclose($fp);

        if(is_file($delfile)) unlink($delfile);//�폜
      }
  	}
  }

  if(is_array($chkno)){
    $line = file(LOGFILE);
    $find = FALSE;
    for($i = 0; $i < count($line); $i++){
      list($no,$res_no,$now,$name,$email,$sub,$com,$url,
           $host,$pw,$ext,$w,$h,$tim,$chk,$color,) = explode(",",$line[$i]);
      if(in_array($no, $chkno)){//�摜�`�F�b�N$chk=1��
        $find = TRUE;
        $line[$i] = "$no,$res_no,$now,$name,$email,$sub,$com,$url,$host,$pw,$ext,$w,$h,$tim,1,$color,\n";
      }
      if($find){//���O�X�V
        $fp = fopen(LOGFILE, "w");
        flock($fp, 2);
        fputs($fp, implode('', $line));
        fclose($fp);
      }
  	}
  }

  // �폜��ʂ�\��
  echo "<input type=\"hidden\" name=\"mode\" value=\"admin\" />\n";
  echo "<input type=\"hidden\" name=\"admin\" value=\"del\" />\n";
  echo "<input type=\"hidden\" name=\"pass\" value=\"$pass\" />\n";
  echo "�폜�������L���̃`�F�b�N�{�b�N�X�Ƀ`�F�b�N�����A�폜�{�^���������ĉ������B\n";
  echo "<table border=\"1\" cellspacing=\"0\">\n";
  echo "<tr bgcolor=\"#6080f6\"><th>�폜</th><th>�L��No</th><th>���e��</th><th>�薼</th>";
  echo "<th>���e��</th><th>�R�����g</th><th>�z�X�g��</th><th>�Y�t<br />(Bytes)</th>";
  if(CHECK) echo "<th>�摜<br />����</th>";
  echo "</tr>";

  $line = file(LOGFILE);

  for($j = 0; $j < count($line); $j++){
    $img_flag = FALSE;
    list($no,$res_no,$now,$name,$email,$sub,$com,$url,
         $host,$pw,$ext,$w,$h,$time,$chk,$color,) = explode(",",$line[$j]);
    // �t�H�[�}�b�g
    list($now,$dmy) = split("\(", $now);
    if($email) $name="<a href=\"mailto:$email\">$name</a>";
    $com = str_replace("<br />"," ",$com);
    $com = htmlspecialchars($com);
    if(strlen($com) > 40) $com = substr($com,0,38) . " ...";
    // �摜������Ƃ��̓����N
    if($ext && is_file($path.$time.$ext)){
      $img_flag = TRUE;
      $clip = "<a href=\".".IMG_DIR.$time.$ext."\" target=\"_blank\">".$time.$ext."</a>";
      $size = filesize($path.$time.$ext);
      $all += $size;			//���v�v�Z
    }else{
      $clip = "";
      $size = 0;
    }
    $bg = ($j % 2) ? "#d6d6f6" : "#f6f6f6";//�w�i�F

    echo "<tr bgcolor=\"$bg\"><th><input type=\"checkbox\" name=\"del[]\" value=\"$no\" /></th>";
    echo "<th>$no</th><td><span class=\"fontS\">$now</span></td><td>$sub</td>";
    echo "<td><strong>$name</strong></td><td><span class=\"fontS\">$com</span></td>";
    echo "<td>$host</td><td align=\"center\">$clip<br />($size)</td>\n";

    if(CHECK){//�摜�`�F�b�N
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
  if(CHECK) $msg = "or������";

  echo "</table><p><input type=\"submit\" value=\"�폜����$msg\" />";
  echo "<input type=\"reset\" value=\"���Z�b�g\" /></form>\n";

  $all = (int)($all / 1024);
  echo "�y �摜�f�[�^���v : <strong>$all</strong> KB �z";
  die("</center></body></html>");
}
/* �I�[�g�����N */
function auto_link($proto){
  $proto = ereg_replace("(https?|ftp|news)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)","<a href=\"\\1\\2\" target=\"_blank\">\\1\\2</a>",$proto);
  return $proto;
}
/* �G���[��� */
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
/* �������[�h */
function search($w, $andor, $target){
  global $path;

  if(get_magic_quotes_gpc()) $w = stripslashes($w); //������

  $log = file(LOGFILE);

  head($dat);
  echo $dat;
  echo "[<a href=\"".PHP_SELF."\">�f���ɖ߂�</a>]
<h3>�������[�h</h3>
�����������P����X�y�[�X�ŋ�؂��ē��͂��Ă��������B<br />
<form action=\"".PHP_SELF."\" method=\"post\">
<input type=\"hidden\" name=\"mode\" value=\"search\" />
<input type=\"text\" name=\"w\" size=\"30\" value=\"".htmlspecialchars($w)."\" />
<select name=\"andor\"><option value=\"and\" selected=\"selected\">AND\n
<option value=\"or\">OR</select>
<input type=\"submit\" value=\"����\" /><br /><br />\n";

  if(trim($w)!=""){// �O��̃X�y�[�X����
    $keys = preg_split("/(�@| )+/", $w);// �������z���
    while(list(,$line) = each($log)){// ���O�𑖍�
      $find = FALSE;
      for($i = 0; $i < count($keys); $i++){
        if($keys[$i]=="") continue;
        if(stristr($line,$keys[$i])){// �}�b�`
          $find = TRUE;
          $line = str_replace($keys[$i],"<b style='color:green;background-color:#ffff66'>$keys[$i]</strong>",$line);
        }elseif($andor == "and"){// AND�̏ꍇ����
          $find = FALSE;
          break;
        }
      }
      if($find) $result[] = $line;	//�}�b�`�������O��z���
    }
    //���ʕ\��
    echo "<div align=\"left\">��������".count($result)."��<br />";
    for($c = 0; $c < count($result); $c++){//���ʓW�J
      list($no,$res_no,$now,$name,$email,$sub,$com,$url,
         $host,$pw,$ext,$w,$h,$time,$chk,$color,) = explode(",", $result[$c]);
      //�t�H�[�}�b�g
      if($url)   $url = "<a href=\"http://$url\" target=\"_blank\">HOME</a>";
      if($email) $name = "<a href=\"mailto:$email\">$name</a>";
      if(LINK) $com = auto_link($com);//�I�[�g�����N
      //���X�̏ꍇ���XNo�ǉ�
      $tit = ($res_no != "") ? "$no $res_no �̃��X" : $no;
      //���ʕ\��
      echo "<hr size=\"1\" />[No.$tit]
<strong>$sub</strong>
Name�F<strong>$name</strong> <span class=\"fontS\">Date�F$now</span> $url<br />
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
	$now0 = preg_replace("/(\(|�i).*(\)|�j)/","",$now);
	$pubdate = date(r, strtotime($now0));
    $desc0 = strip_tags($com);
    $desc = htmlspecialchars($desc0);//�^�O���֎~
	//$desc = mb_strimwidth($desc0,0,500,"...","Shift_JIS");	//������ۂ߂�
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
  // UTF-8�ɕϊ�
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