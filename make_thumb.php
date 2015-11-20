<?
/* 
#	CMS 		: make_thumb.php
#	Created on 	: 20-11-2015
#	Author 		: Goncharov S.
#	Company 	: Ssmart Lab.
#	Site 		: ssmart.ru
*/
// start time
$startTime = microtime(1);

// variabals
$parsDir = "."; // "." - this directory
$smallPrefix = 'small';
$extFiles = 'jpg';
$w = '250';
$h = '250';

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
		echo 'некорректный размер файла';
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
$filelist = array();
if ($handle = opendir($parsDir)) {
	while ($inFile = readdir($handle)) {
		if (is_file($inFile)) {
			$isin = explode('.', $inFile);
			$isno = explode('_', $inFile);
			if ($isin[1] == $extFiles && $isno[0] != $smallPrefix) {
				echo $inFile." \r\n";
				$outFile = $smallPrefix.'_'.$inFile;
				if(myImage($inFile, $outFile, $w, $h) == 0) {
					echo "Complit! \r\n";
				}
			}
			
		}
	}
	closedir($handle);
}

// print_r($filelist);


// cms report
$fimalTime = microtime(1);
$totaltime=round(($fimalTime-$startTime),5);
echo "Tota time: " . $totaltime . " sec \n\r";

if(function_exists('memory_get_peak_usage'))
	echo "Memory peak: " . round((memory_get_peak_usage() / 1024),2)  . " Kb \n\r";

?>