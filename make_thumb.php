<?
/* 
#	CMS 		: make_thumb.php
#	Created on 	: 20-11-2015
#	Author 		: Goncharov S.
#	Company 	: Ssmart Lab.
#	Site 		: ssmart.ru
#	
#	после применения файла у вас создадутся 2 категории с резервными копиями измененных файлов и папка с измененными файлами
*/


// start time
$startTime = microtime(1);

//arguments
// направления [ConvertToMini, addMini, pngToGpg] 
$napravlenie = $argv['1'];
$newDir = date('m-Y');
if (!is_dir($newDir)) {
	mkdir($newDir, 0777);
}

$backDir = 'bak_'.date('m-Y');
if (!is_dir($backDir)) {
	mkdir($backDir, 0777);
}

// variabals
$parsDir = "."; // "." - this directory

// добавления к имени файла в конец файла
$smallPrefix = '';
$betweenPrefix = ''; 

//расширения файлов
if ($napravlenie == 'pngToGpg') {
	$extFiles = array('png');
} else {
	$extFiles = array('jpg', 'JPG', 'png');
}


// префикс для старых файлов
$oldImgPrefif = 'old';

// размеры результирующих файлов
$w = ''; // ширина
$h = '1024'; // высота

// creat image functions
function myImage($inFile, $outFile, $w, $h) {
	list($w_i, $h_i, $type) = getimagesize($inFile);
	if (!$w_i || !$h_i) {
		//echo 'Невозможно получить длину и ширину изображения';
		return $err = '1';
	}
	$types = array('','gif','jpeg','png');
	$ext = $types[$type];
	if ($ext) {
		$func = 'imagecreatefrom'.$ext;
		$img = $func($inFile);
	} else {
		//echo 'Некорректный формат файла';
		return $err = '2';
	}
	//проверям, вдруг изображение изначально меньше нам нужного
	if ($w_i < $w || $h_i < $h) {
		echo 'Wrong size '.$w_i.' < '.$w.' || '.$h_i.' < '.$h." \r\n";
		return $err = '3';
	}

	//вычисляем стороны и сдвиги
	$outSize = array();
	//определяем ориентацию
	if ($w_i < $h_i) {
		$Pr = round($w/$w_i, 4);
		$outSize[w] = $w;
		$outSize[h] = $h_i*$Pr; //высота получится больше чем надо
		$outSize[x] = 0;
		$outSize[y] = $outSize[h]-$h; //сдвиг по y
	} else {
		$Pr = round($h/$h_i, 4);
		$outSize[w] = $w_i*$Pr;
		$outSize[h] = $h;
		$outSize[x] = $outSize[w]-$w; //сдвиг по x
		$outSize[y] = 0;
	}

	// ресэмплирование
	$image = $img;
	$image_o = imagecreatetruecolor($w, $h);
	imagecopyresampled($image_o, $image, 0, 0, $outSize['x'], $outSize['y'], $outSize['w'], $outSize['h'], $w_i, $h_i);
	imagejpeg($image_o,$outFile,100);
	unset($image_o, $image);
	
	return $err = 0;
}
// pars dir files
function presentative($betweenPrefix, $isin, $smallPrefix, $extFiles) {
	$result = array();
	if ($betweenPrefix != '') { 
		$noSmall = explode($betweenPrefix, $isin[0]);
		if ($noSmall[1] == $smallPrefix) {
			$result[0] = 1;
			$result[1] = "Wrong NAME file: ". $isin[0].'.'.$isin[1]." \r\n";
			return $result;
		}
	} 
	if (in_array($isin[1], $extFiles)) {
		$result[0] = 0;
	} else {
		$result[0] = 1;
		$result[1] = "Wrong EXT file: ". $isin[1]." \r\n";
	}
	return $result;
}

$filelist = array();
if ($handle = opendir($parsDir)) {
	while ($inFile = readdir($handle)) {
		if (is_file($inFile)) {
			$isin = explode('.', $inFile);
			$myRes = presentative($betweenPrefix, $isin, $smallPrefix, $extFiles);
			if ($myRes[0] == 0) {
				echo $inFile." \r\n";
				$outFile = $newDir.'/'.$isin[0].$betweenPrefix.$smallPrefix.'.jpg';
				if ($w == '' || $h == '') {
					list($w, $h) = getimagesize($inFile);
					if(myImage($inFile, $outFile, $w, $h) == 0) {
						echo "Complit! ".$inFile."\r\n";
						copy($inFile, $backDir.'/'.$inFile);
					} 
					$w=$h='';
				} else {
					if(myImage($inFile, $outFile, $w, $h) == 0) {
						echo "Complit! ".$inFile."\r\n";
						copy($inFile, $backDir.'/'.$inFile);
					} 
				}
			} else {
				echo $myRes[1];
			}
			
		}
	}
	closedir($handle);
}

// cms report
$fimalTime = microtime(1);
$totaltime=round(($fimalTime-$startTime),5);
echo "Tota time: " . $totaltime . " sec \n\r";

if(function_exists('memory_get_peak_usage'))
	echo "Memory peak: " . round((memory_get_peak_usage() / 1024),2)  . " Kb \n\r";

?>