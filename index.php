<?php


$input_params = [
	[
		'text' => 'Текст красного цвета',
	 	'cells' => '2,1,3',
	 	'align' => 'center',
	 	'valign' => 'top',
	 	'color' => '#FF0000',
	 	'bgcolor' => '#0000FF'
 	], 
	[ 
		'text' => 'Текст зеленого цвета',
	 	'cells' => '8,9',
	 	'align' => 'left',
	 	'valign' => 'bottom',
	 	'color' => '#FFF',
	 	'bgcolor' => 'green'
	],
	[ 
		'text' => 'Текст зеленого цвета',
	 	'cells' => '12,13,14,15',
	 	'align' => 'right',
	 	'valign' => 'middle',
	 	'color' => 'black',
	 	'bgcolor' => 'red'
	]
];

	$col_cnt = 5;
	$row_cnt = 3;

	$box_size = 100;
	$wrap_width = $col_cnt * $box_size;
	$wrap_height = $row_cnt * $box_size;

function generate( $params ) {

	global $col_cnt;
	global $row_cnt;

	global $box_size;
	global $wrap_width;
	global $wrap_height;

	$cells = [];
	$base = [];


for($i = 0; $i < count($params); $i++) {
	$block[] = explode(',', $params[$i]['cells']);
	sort($block[$i]);
}

for ($i=0; $i < $row_cnt; $i++) { 
	for ($j=1; $j <= $col_cnt; $j++) { 
		$cell = ($j+$i*$col_cnt);	
		$cells[] = $cell;
	}
}

for ($i=0; $i < count($block); $i++) { 
	$rowspan = 1;
	$colspan = 1;
	for ($j=0; $j < count($block[$i])-1; $j++) { 
		unset($cells[array_search($block[$i][$j], $cells)]);
		if(floor(($block[$i][$j]-1)/$col_cnt) == floor(($block[$i][$j+1]-1)/$col_cnt)){
			$colspan++;
			unset($cells[array_search($block[$i][$j+1], $cells)]);
		} else {
			$rowspan++;
			$colspan = 1;
			unset($cells[array_search($block[$i][$j+1], $cells)]);
		}
	}
	$block[$i]['colspan'] = $colspan;
	$block[$i]['rowspan'] = $rowspan;
	$block[$i]['attr'] = $params[$i];
}

    //echo '<pre>';
    //print_r($block);
    //echo '</pre>';
    //print_r($cells);

$html = "<table class='wrapper'>";

$cnt = 1;
for ($i=1; $i <= $row_cnt; $i++) { 
	$html .= "<tr>";
	for ($j=$cnt; $j <= $i*$col_cnt; $j++) { 
		for ($k=0; $k < count($block); $k++) { 
			if($j == $block[$k][0]){
				
				$styles = '';
				$styles .= 
				"text-align:" . $block[$k]['attr']['align'] .
				"; vertical-align:" . $block[$k]['attr']['valign'] .
				"; color:" . $block[$k]['attr']['color'] .
				"; background-color:" . $block[$k]['attr']['bgcolor'];

				$html .= "<td colspan=".$block[$k]['colspan']." rowspan=".$block[$k]['rowspan']." style='" . $styles . "'>" . $block[$k]['attr']['text'] . "</td>";
			} 
		}
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
1212
	<div class="container">
		<div class="row">
			<table>
				<?=generate( $input_params )?>
			</table>
		</div>
	</div>

</body>
</html>