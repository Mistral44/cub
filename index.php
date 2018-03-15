<?php

$input_params = [
	[
		'text' => 'Текст красного цвета',
	 	'cells' => '2,4,3',
	 	'align' => 'center',
	 	'valign' => 'top',
	 	'color' => '#FF0000',
	 	'bgcolor' => '#0000FF'
 	],
 	[
		'text' => 'Текст красного цвета',
	 	'cells' => '12,11,13',
	 	'align' => 'center',
	 	'valign' => 'top',
	 	'color' => '#FF0000',
	 	'bgcolor' => '#0000FF'
 	],
 	[
		'text' => 'Текст красного цвета',
	 	'cells' => '10,15',
	 	'align' => 'center',
	 	'valign' => 'top',
	 	'color' => '#FF0000',
	 	'bgcolor' => '#0000FF'
 	]
];

	$col_cnt = 5; // Количество ячеек в строке
	$row_cnt = 3; // Количество строк в таблице
	$box_size = 100;
	$wrap_width = $col_cnt * $box_size;
	$wrap_height = $row_cnt * $box_size;

function generate( $params ) {

	global $col_cnt;
	global $row_cnt;
	global $box_size;
	global $wrap_width;
	global $wrap_height;

	$cells = []; // Массив всех ячеек в таблице

// Цикл по строкам таблицы
for ($i=0; $i < $row_cnt; $i++) { 
	for ($j=1; $j <= $col_cnt; $j++) { 
		$cell = ($j+$i*$col_cnt);
		$cells[] = $cell; 
	}
}

// В данном цикле выбираются значение из поля "cells" и возвращают их в качестве отсортированого массива
for($i = 0; $i < count($params); $i++) {
	$block[] = explode(',', $params[$i]['cells']);
	sort($block[$i]);
}

// Определяем размер генерируемых блоков и последующее удаление данных ячеек из общего массива ячеек 
for ($i=0; $i < count($block); $i++) { 
	$rowspan = 1;
	$colspan = 1;
	for ($j=0; $j < count($block[$i])-1; $j++) { 
		if(floor(($block[$i][$j]-1)/$col_cnt) == floor(($block[$i][$j+1]-1)/$col_cnt)){
			$colspan++;
			unset($cells[array_search($block[$i][$j+1], $cells)]);
		} else {
			$rowspan++;
			$colspan = 1;
			unset($cells[array_search($block[$i][$j+1], $cells)]);
		}
	}
	unset($cells[array_search($block[$i][0], $cells)]);

	// Полученые данные заносим в массив каждого блока
	$block[$i]['colspan'] = $colspan;
	$block[$i]['rowspan'] = $rowspan;
	// Также заносим передаваемые атрибуты для блоков
	$block[$i]['attr'] = $params[$i];
}

$html = "<table class='wrapper'>";
// Генерируем таблицу
$cnt = 1;
for ($i=1; $i <= $row_cnt; $i++) { 
	$html .= "<tr>";
	for ($j=$cnt; $j <= $i*$col_cnt; $j++) {
		// Проверяем не входят ли ячейки в генерируемые блоки
		for ($k=0; $k < count($block); $k++) { 
			if($j == $block[$k][0]){
				// Если входят, то выводим блок с задаными размерами и стилями 
				$styles = '';
				$styles .= 
				"text-align:" . $block[$k]['attr']['align'] .
				"; vertical-align:" . $block[$k]['attr']['valign'] .
				"; color:" . $block[$k]['attr']['color'] .
				"; background-color:" . $block[$k]['attr']['bgcolor'];

				$html .= "<td colspan=".$block[$k]['colspan']." rowspan=".$block[$k]['rowspan']." style='" . $styles . "'>" . $block[$k]['attr']['text'] . "</td>";
			} 
		}
		// Выводим ячеки которые не входят в получаемые блоки 
		if(in_array($j, $cells)){
			$html .= "<td>" . $j. "</td>";
		}
		$cnt++;	
	}
	$html .= "</tr>";
}
$html .= '</table>';
return $html;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Test</title>
	<link rel="stylesheet" href="bootstrap.min.css">

<style>
	.wrapper {
		width: <?=$wrap_width?>px;
		height: <?=$wrap_height?>px;
		margin: 100px auto;
		border-collapse: collapse;
	}

	td {
		height: <?=$box_size?>px;
		width: <?=$box_size?>px;
		border: 1px solid #000;
		text-align: center;
	}
</style>

</head>
<body>

	<div class="container">
		<div class="row">
			<table>
				<?=generate( $input_params )?>
			</table>
		</div>
	</div>

</body>
</html>