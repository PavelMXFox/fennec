<?php

function txt2html($txt) {

  //We need some HTML entities back!
  $txt = str_replace('&','&amp;',$txt);
  $txt = str_replace('<','&lt;',$txt);
  $txt = str_replace('>','&gt;',$txt);
  $txt = str_replace("\n","<br/>",$txt);
 
  return $txt;
}

// получить значение с get или post
function getVal($name, $regex = '',$skipQuotes=null)
{
    global $_POST, $_GET;
    if ((!isset($_POST[$name]))&&(!isset($_GET[$name])))
    {
    	return null;
    } 
    if ($regex != "") {
    	if (isset($_POST[$name])) { $val = preg_replace('![^'.$regex.']+!', '', $_POST[$name]); } else {$val = ""; }
    if ($val == "") {
        if (isset($_GET[$name])) {$val = preg_replace('![^'.$regex.']+!', '', $_GET[$name]);};
    }
    } else {

	if (!isset($skipQuotes)){
    if (isset($_POST[$name])) { $val = preg_replace("![\'\"]+!", '\"', $_POST[$name]);} else {$val="";}
    if ($val == "") {
        if (isset($_GET[$name])) {$val = preg_replace("![\'\"]+!", '\"', $_GET[$name]);}
    }
    } else {
        if (isset($_POST[$name])) { $val = $_POST[$name];}
    else {
        $val = $_GET[$name];
    }
    
    }
    }
    return $val;
    
}

function dropcslash($val)
{
    $val = preg_replace("!\\\([\'\"])+!", "$1", $val);
    return $val;
}

/**
 * проверяем, что функция mb_ucfirst не объявлена
 * и включено расширение mbstring (Multibyte String Functions)
 */

function mbx_ucfirst($str, $encoding='UTF-8')
{
    $str = mb_ereg_replace('^[\ ]+', '', $str);
    $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
           mb_substr($str, 1, mb_strlen($str), $encoding);
    return $str;
}


function getGUIDc()
{
    mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $hyphen = chr(45);// "-"
    $uuid = substr($charid, 0, 8).$hyphen
	   .substr($charid, 8, 4).$hyphen
           .substr($charid,12, 4).$hyphen
           .substr($charid,16, 4).$hyphen
           .substr($charid,20,12);

        return $uuid;
}

function getGUID()
{
    $cUuid = getGUIDc();
        $uuid = chr(123)// "{"
            .$cUuid
            .chr(125);// "}"
        return $uuid;


}

function iso_date2datew($isodate)
{
	if ($isodate === null || $isodate == '')
	{
		return "Never";	
	}
	$t = strtotime($isodate);
    $marr = ['мартобря','января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];
	 $dow=['ХЗ','Пн','Вт','Ср','Чт','Пт','Сб','Вс'];

    $isodate = preg_replace('![^0-9]+!', '', $isodate);

    $date = $dow[date("N",$t)]." ". (substr($isodate,6,2)+0)." "
	    .$marr[(substr($isodate,4,2)+0)]." "
	    .substr($isodate,0,4)." г.";

return $date;
}

function iso_date2date($isodate)
{
	if ($isodate === null || $isodate == '')
	{
		return "Never";	
	}
    $marr = ['мартобря','января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];

    $isodate = preg_replace('![^0-9]+!', '', $isodate);

    $date = (substr($isodate,6,2)+0)." "
	    .$marr[(substr($isodate,4,2)+0)]." "
	    .substr($isodate,0,4)." г.";

return $date;
}

function iso_date2datetime($isodate)
{
    $marr = ['мартобря','января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];

    $isodate = preg_replace('![^0-9]+!', '', $isodate);

    $date = (substr($isodate,6,2)+0)." "
	    .$marr[(substr($isodate,4,2)+0)]." "
	    .substr($isodate,0,4)." г. "
	    .substr($isodate,8,2).":"
	    .substr($isodate,10,2).":"
	    .substr($isodate,12,2);

return $date;
}

function iso_date2datetimew($isodate)
{
	$t = strtotime($isodate);
    $marr = ['мартобря','января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];
	 $dow=['ХЗ','Пн','Вт','Ср','Чт','Пт','Сб','Вс'];

    $isodate = preg_replace('![^0-9]+!', '', $isodate);

    $date = $dow[date("N",$t)]." ".(substr($isodate,6,2)+0)." "
	    .$marr[(substr($isodate,4,2)+0)]." "
	    .substr($isodate,0,4)." г. "
	    .substr($isodate,8,2).":"
	    .substr($isodate,10,2).":"
	    .substr($isodate,12,2);
	    

return $date;
}

function iso_date2datetimesw($isodate)
{
	$t = strtotime($isodate);
    $marr = ['мтб','янв','фев','мар','апр','мая','июн','июл','авг','сен','окт','ноя','дек'];
	 $dow=['ХЗ','Пн','Вт','Ср','Чт','Пт','Сб','Вс'];

    $isodate = preg_replace('![^0-9]+!', '', $isodate);

    $date = $dow[date("N",$t)]." ".(substr($isodate,6,2)+0)." "
	    .$marr[(substr($isodate,4,2)+0)]." "
	    .substr($isodate,0,4)." г. "
	    .substr($isodate,8,2).":"
	    .substr($isodate,10,2).":"
	    .substr($isodate,12,2);
	    

return $date;
}

function iso_date2dates($isodate)
{
		if ($isodate === null || $isodate == '')
	{
		return "Never";	
	}
    $marr = ['мтб','янв','фев','мар','апр','мая','июн','июл','авг','сен','окт','ноя','дек'];

    $isodate = preg_replace('![^0-9]+!', '', $isodate);

    $date = (substr($isodate,6,2)+0)." "
	    .$marr[(substr($isodate,4,2)+0)]." "
	    .substr($isodate,0,4)." г.";

return $date;
}

function iso_date2datesz($isodate)
{
    $marr = ['мтб','янв','фев','мар','апр','мая','июн','июл','авг','сен','окт','ноя','дек'];

    $isodate = preg_replace('![^0-9]+!', '', $isodate);
    if ((substr($isodate,6,2)+0) < 10) {$add = "0";} else {$add = "";};
    $date = $add.(substr($isodate,6,2)+0)." "
	    .$marr[(substr($isodate,4,2)+0)]." "
	    .substr($isodate,0,4)." г.";

return $date;
}

function stamp2iso_date($stamp=null)
{
    if (empty($stamp)) { $stamp = time(); }
    return date("Y-m-d H:i:s",$stamp);
}

function iso_date2datesny($isodate)
{
    $marr = ['мтб','янв','фев','мар','апр','мая','июн','июл','авг','сен','окт','ноя','дек'];

    $isodate = preg_replace('![^0-9]+!', '', $isodate);

    $date = (substr($isodate,6,2)+0)." "
	    .$marr[(substr($isodate,4,2)+0)];

return $date;
}

function iso_date2stamp($isodate)
{ 
// 01234567890123
// 20180901235959
    $isodate = preg_replace('![^0-9]+!', '', $isodate);

    //$date = (substr($isodate,6,2)+0)." "
//	    .$marr[(substr($isodate,4,2)+0)];
    $date = mktime(intval((substr($isodate,8,2))), intval(substr($isodate,10,2)),intval(substr($isodate,12,2)), intval((substr($isodate,4,2))), intval((substr($isodate,6,2))), intval(substr($isodate,0,4)));

	return $date;
}

function getCurrStamp()
{
	return time();
}


function fullname2qname($first, $mid, $last)
{
	return $last." ".mb_substr($first,0,1).". ".mb_substr($mid,0,1).".";
}

function text2html($src) {
	
	$src=preg_replace("/[\n]/","</br>",$src);
//	$src=preg_replace("/[\cr\<\>]/"," ",$src);
	return $src;
}


function genPasswd($number, $arr=null)
{
 	if (!isset($arr))
  	{
    $arr = array('a','b','c','d','e','f',
                 'g','h','i','j','k','l',
                 'm','n','o','p','r','s',
                 't','u','v','x','y','z',
                 '1','2','3','4','5','6',
                 '7','8','9','0');
   }              
    // Генерируем пароль
    $pass = "";
    for($i = 0; $i < $number; $i++)
    {
      // Вычисляем случайный индекс массива
      $index = rand(0, count($arr) - 1);
      $pass .= $arr[$index];
    }
    return $pass;
}
  
function intToTime($val) {
	$hrs = intdiv ($val , 60 );
	$mins = $val-($hrs*60);
	
	$retval="$hrs:".sprintf("%'.02d",$mins);
	return $retval;
}
	
function timeOfDay2sec($timestring) {
	$hrs = +(substr($timestring, 0, 2));
	$min = +(substr($timestring, 3, 2));
	$sec = +(substr($timestring, 6, 2));
	$stampsec = ($hrs*3600)+($min*60)+$sec;
	
	return $stampsec;
}

function sec2timeOfDay($isec) {
	$hrs = floor($isec/3600);
	$min = floor(($isec - ($hrs*3600))/60);
	$sec = $isec - ($hrs*3600) - ($min*60);
	
	return "$hrs:$min:$sec";
}

function isWorkDay($searchTimestamp,$workDaysOfWeek,$calendarOverride,$ignoreHolidays = false)
{
	//$searchTimestamp = strtotime($startISODATETIME);
	
	if ($ignoreHolidays)
	{
		return true;	
	}
	// 1. Определяем день недели
	$dayOfWeek = date("N",$searchTimestamp);

	// 2. проверяем вхождение для в список рабочих дней недели или исключений
	$searchDate = date("Y-m-d",$searchTimestamp);

	if (array_key_exists($searchDate,$calendarOverride))
	{	
		$isWorkDay = $calendarOverride[$searchDate];
	} else {	
		$isWorkDay = preg_match ( "/".$dayOfWeek."/" , $workDaysOfWeek);
	}
	return $isWorkDay; 
}
	
function getFormatByFilename($filename)
{
		$f_arr = explode('.', $filename);
		$ext = strtoupper($f_arr[count($f_arr)-1]);
		$color="navy";
		$is_attachment = true;
		$ctype = "application/octet-stream";
		
		if ($ext == 'PDF') { $icon = 'far fa-file-pdf'; $alt = 'PDF Document';$color="#ED0C0C"; $is_attachment = false; $ctype = "application/pdf";}
		elseif ($ext == 'ODT' || $ext == 'DOC' || $ext == 'DOCX') { $icon ='far fa-file-word' ;$alt= 'Текстовый документ';$color="#070EC4";}
		elseif ($ext == 'TXT' || $ext == 'TEXT') { $icon ='far fa-file-word' ;$alt= 'Текстовый документ';$color="#070EC4"; $is_attachment = true;}
		elseif ($ext == 'ODS' || $ext == 'XLS' || $ext == 'XLSX') { $icon ='far fa-file-excel' ;$alt= 'Табличный документ'; $color="#079F00";}
		elseif ($ext == 'PNG' || $ext == 'JPG' || $ext == 'JPEG' || $ext == 'GIF' || $ext == 'TIFF') { $icon = 'far fa-file-image';$alt= 'Изображение'; $color="#FF6600"; $is_attachment = false; $ctype = "image";}
		elseif ($ext == 'MP3' || $ext == 'WAV' ) { $icon = 'far fa-file-audio';$alt= 'Аудиофайл';}
		elseif ($ext == 'AVI' || $ext == 'MP4') { $icon = 'far fa-file-video';$alt= 'Видеофайл';}
		else { $icon = 'far fa-file';$alt= 'Файл';};
		$res = array("icon"=>$icon, "alt"=>$alt, "color"=>$color, "is_attachment" => $is_attachment, "ctype"=>$ctype);
		return $res;
}

  
function validateEMail($string)
{
	return preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9.]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", $string); 
}	

function inet_aton($ip_address)
{
	$subject = "abcdef"; 
	$pattern = '/^def/'; 
	$ip_address = preg_replace('![^'.'0-9.'.']+!', '', $ip_address); 
	preg_match('/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$/',$ip_address, $arr); 
	if (!(count($arr) ==5 && $arr[1]<256 && $arr[2]<256 && $arr[3]<256 && $arr[4] < 256)) { return false; }
	return $arr[1]*16777216 + $arr[2] * 65536 + $arr[3]*256 + $arr[4];
}
	
function mac2bin($mac_address)
{
	$mac_address =preg_replace('![^'.'0-9A-Fa-f'.']+!', '', $mac_address);
	if (strlen($mac_address) != 12) { return false; }
	return hex2bin($mac_address);	
}

?>