<?php
/*Дан каталог с подкаталогами, содержащими файлы с разными именами. Также имеется файл main_list_name.txt с именами файлов. 

Требуется вывести имена и пути к файлам в каталоге, имена которых отличаются от списка с main_list_name.txt на 10% и более.*/


// функция возвращает многомерный массив, в котором элементы представлены в  виде "имя файла => директория" 
function fileList($dir) {
	$file_list = [];
	if ($handle = opendir($dir)) {
		while (($f=readdir($handle)) !== false) { 
			if ($f != '.' AND $f != '..') {
				if (is_dir($dir.'/'.$f)) {
					$dir2 = $dir.'/'.$f;
					$file_list[] = fileList($dir2);
				}else {
					$file_list[$f] = $dir.'/';
				}
			}
		}
		closedir($handle);
	}
	return array_flat($file_list);
}

// функция принимает на вход монгомерный массив и возвращает простой ассоциативный массив
function array_flat($arr) { 

  foreach($arr as $key => $value) { 
    if(is_array($value)) { 
      $tmp = array_merge($tmp, array_flat($value)); 
    } 
    else { 
      $tmp[$key] = $value; 
    } 
  } 

  return $tmp; 
} 

// функция принимает на вход имя директории $dir, имя файла со списком $file и параметр $per, и возвращает массив имен файлов из $dir (в виде "имя файла => директория"), которые отличаются от имен файлов в списке $file на $per процентов и более 
function resultСompare ($dir, $file, $per) {
	$filesInDir = fileList($dir);
	$filesInList = explode(',', file_get_contents($file));
	$res = [];
	foreach ($filesInDir as $f1 => $path) {
		foreach ($filesInList as $f2) {
			similar_text($f1, $f2, $percent); 
				if ($percent >= $per) {
					$res[$f1] = $path;
					break;
				}
		}
	}
	return $res;
}

// получаем массив в виде "имя файла => директория"
$res = resultСompare ('main_dir', 'main_list_name.txt', 10);

// выводим плученный массив в браузер  
foreach ($res as $file => $path) {
	echo "файл  => <b> " . $file . " </b> </br>
	  	  директория => <b>" . $path . "</b> </br><hr>";
}








