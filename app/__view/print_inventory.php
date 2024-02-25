<h1>재고표</h1>
<ul>
	<li>조회기간 : <?=date('Y-m-d', $start)?> ~ <?=date('Y-m-d', $end)?></li>
</ul>
<table class="table table-sm table-bordered text-center small">
	<thead>
		<tr>
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
	</thead>
	<tbody>
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
			<td class="text-center"><?=$i++?></td>
			<td class="text-center"><?=$pd['category_code']?></td>
			<td><?=$pd['category_name']?></td>
			<td class="text-center"><?=$pd['product_code']?></td>
			<td class="text-center"><?=$pd['product_color']?></td>
			<td class="text-center"><?=$pd['product_size']?></td>
			<td class="text-right"><?=number_format($pd['basic'])?></td>
			<td class="text-right"><?=number_format($pd['input'])?></td>
			<td class="text-right"><?=number_format($pd['output'])?></td>
			<td class="text-right"><?=number_format($pd['defect'])?></td>
			<td class="text-right font-weight-bold"><?=number_format($pd['stock'])?></td>
			<td class="text-right"><?=number_format($pd['po'])?></td>
			<td class="text-right"><?=number_format($pd['inven_amount'])?></td>
		</tr>
<?}?>
<?}?>
	</tbody>
	<tfoot>
		<tr>
			<td><?=count($invens)?></td>
			<td colspan="5"></td>
			<td class="text-right"><?=number_format($sum_before_qty)?></td>
			<td class="text-right"><?=number_format($sum_input_qty)?></td>
			<td class="text-right"><?=number_format($sum_output_qty)?></td>
			<td class="text-right"><?=number_format($sum_defect_qty)?></td>
			<td class="text-right"><?=number_format($sum_stock_qty)?></td>
			<td class="text-right"><?=number_format($sum_po_qty)?></td>
			<td class="text-right"><?=number_format($sum_inven_amount)?></td>
		</tr>
	</tfoot>
</table>