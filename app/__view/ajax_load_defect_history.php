<div class="modal-header">
	<h5 class="modal-title" id="historyModalLabel">불량 내역</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
<div class="modal-body">
	<table class="table table-sm table-bordered table-striped text-center">
		<thead>
			<tr>
				<th>날짜</th>
				<th>개수</th>
			</tr>
		</thead>
		<tbody>
<?
if($defects){
	$sum_defect_qty = 0;
	foreach($defects as $ip){
		$sum_defect_qty+= $ip['defect_qty'];
?>
			<tr>
				<td><?=date('Y-m-d', $ip['defect_time'])?></td>
				<td class="text-right"><?=number_format($ip['defect_qty'])?></td>
			</tr>
<?}?>
<?}else{?>
			<tr>
				<td colspan="2" class="text-center text-danger">불량 내역이 없습니다.</td>
			</tr>
<?}?>
		</tbody>
		<tfoot>
			<tr>
				<th>합계</th>
				<th class="text-right"><?=number_format($sum_defect_qty)?></th>
			</tr>
		</tfoot>
	</table>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
</div>