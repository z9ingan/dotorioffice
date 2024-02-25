<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
<meta http-equiv="Content-Type" content="application/vnd.ms-excel; charset=UTF-8">
<xml>
	<x:ExcelWorkbook>
		<x:ExcelWorksheets>
			<x:ExcelWorksheet>
				<x:Name>재고현황</x:Name>
				<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions>
			</x:ExcelWorksheet>
		</x:ExcelWorksheets>
	</x:ExcelWorkbook>
</xml>
</head>
<body>
<table border='1px'>
	<tr style="height:50px;">
		<th scope="col">번호</th>
		<th scope="col">제품코드</th>
		<th scope="col">제품명</th>
		<th scope="col">품목코드</th>
		<th scope="col">컬러</th>
		<th scope="col">사이즈</th>
		<th scope="col">이월재고</th>
		<th scope="col">입고</th>
		<th scope="col">출고</th>
		<th scope="col">불량</th>
		<th scope="col">현재재고</th>
		<th scope="col">입고예정</th>
		<th scope="col">재고금액</th>
	</tr>
<?
	$i = 1;
	$sum_before_qty = 0;
	$sum_input_qty = 0;
	$sum_output_qty = 0;
	$sum_defect_qty = 0;
	$sum_stock_qty = 0;
	$sum_po_qty = 0;
	if($invens){ // 입고항목이 있으면
		foreach($invens as $pd){
			$sum_before_qty+= $pd['basic'];
			$sum_input_qty+= $pd['input'];
			$sum_output_qty+= $pd['output'];
			$sum_defect_qty+= $pd['defect'];
			$sum_stock_qty+= $pd['stock'];
			$sum_po_qty+= $pd['po'];
?>
	<tr>
		<td style="text-align:center;"><?=$i++?></td>
		<td style="text-align:center;"><?=$pd['category_code']?></td>
		<td><?=$pd['category_name']?></td>
		<td style="text-align:center;"><?=$pd['product_code']?></td>
		<td style="text-align:center;"><?=$pd['product_color']?></td>
		<td style="text-align:center;"><?=$pd['product_size']?></td>
		<td style="text-align:right;"><?=number_format($pd['basic'])?></td>
		<td style="text-align:right;"><?=number_format($pd['input'])?></td>
		<td style="text-align:right;"><?=number_format($pd['output'])?></td>
		<td style="text-align:right;"><?=number_format($pd['defect'])?></td>
		<td style="text-align:right; font-weight:bold;"><?=number_format($pd['stock'])?></td>
		<td style="text-align:right;"><?=number_format($pd['po'])?></td>
		<td style="text-align:right;"><?=number_format($pd['inven_amount'])?></td>
	</tr>
<?}?>
	<tr>
		<td><?=count($invens)?></td>
		<td colspan="5"></td>
		<td style="text-align:right;"><?=number_format($sum_before_qty)?></td>
		<td style="text-align:right;"><?=number_format($sum_input_qty)?></td>
		<td style="text-align:right;"><?=number_format($sum_output_qty)?></td>
		<td style="text-align:right;"><?=number_format($sum_defect_qty)?></td>
		<td style="text-align:right;"><?=number_format($sum_stock_qty)?></td>
		<td style="text-align:right;"><?=number_format($sum_po_qty)?></td>
		<td style="text-align:right;"><?=number_format($sum_inven_amount)?></td>
	</tr>
<?}?>
</table>

</body>
</html>