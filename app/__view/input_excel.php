<div class="container-xxl py-3" style="height:calc(100vh - 3.5rem);">
	<div class="d-flex align-items-center justify-content-between mb-3">
		<h2 class="h4 mb-0">입고 (엑셀 모드)</h2>
		<div>
			<button type="button" class="btn btn-outline-success" title="엑셀로 등록하기" data-toggle="modal" data-target="#excelModal"><i class="feather icon-file-plus"></i></button>
		</div>
	</div>
	<div class="alert alert-info">
		<i class="feather icon-alert-triangle"></i> 입고내용이 맞는지 꼭 검토하고 진행해주세요.
	</div>
	<form method="post" id="excelControlForm" class="table-responsive scrollbar-macosx">
		<table class="table table-sm table-bordered table-hover text-center align-middle small text-nowrap">
			<thead class="table-light">
				<tr>
					<th class="text-nowrap" style="width:1%;">
						<div class="form-check mb-0 ml-1">
							<input class="form-check-input select-all" type="checkbox" id="select-all">
							<label class="form-check-label" for="select-all">
						</div>
					</th>
					<th class="text-nowrap" style="width:1%;">번호</th>
					<th>입고일자</th>
					<th>품목코드</th>
					<th>제품명</th>
					<th>컬러</th>
					<th>사이즈</th>
					<th>수량</th>
					<th>단가</th>
					<th>금액</th>
					<th>세액</th>
					<th>합계</th>
				</tr>
			</thead>
			<tbody>
<?
	foreach($excels as $i => $ex){
?>
				<tr<?if(!$ex['product']){?> class="table-danger"<?}?>>
					<td>
						<div class="form-check mb-0 ml-1">
							<input name="excel_no[<?=$i?>]" class="form-check-input select-each" type="checkbox" id="select-each<?=$i?>" value="<?=$i?>"<?if(!$ex['product']){?> disabled<?}else{?> checked<?}?>>
							<label class="form-check-label" for="select-each<?=$i?>">
						</div>
					</td>
					<td class="text-nowrap text-center"><?=$i+1?></td>
					<td>
						<input type="hidden" name="excel_time[<?=$i?>]" value="<?=$input_time?>">
						<?=$input_time?>
					</td>
					<td<?if(!$ex['product']){?> class="text-danger font-weight-bold"<?}?>>
						<input type="hidden" name="excel_code[<?=$i?>]" value="<?=$ex['excel_code']?>">
						<?=$ex['excel_code']?>
					</td>
<?if(!$ex['product']){?>
					<td colspan="3" class="text-danger font-weight-bold">품목코드와 일치하는 정보가 없습니다.</td>
<?}else{?>
					<td><?=$ex['product']['category_name']?></td>
					<td><?=$ex['product']['product_color']?></td>
					<td><?=$ex['product']['product_size']?></td>
<?}?>
					<td class="text-right">
						<input type="hidden" name="excel_qty[<?=$i?>]" value="<?=$ex['excel_qty']?>">
						<?=number_format($ex['excel_qty'])?>
					</td>
					<td class="text-right">
						<input type="hidden" name="excel_price[<?=$i?>]" value="<?=$ex['excel_price']?>">
						<?=number_format($ex['excel_price'])?>
					</td>
					<td class="text-right">
						<input type="hidden" name="excel_amount[<?=$i?>]" value="<?=$ex['excel_amount']?>">
						<?=number_format($ex['excel_amount'])?>
					</td>
					<td class="text-right">
						<input type="hidden" name="excel_tax[<?=$i?>]" value="<?=$ex['excel_tax']?>">
						<?=number_format($ex['excel_tax'])?>
					</td>
					<td class="text-right">
						<input type="hidden" name="excel_tamount[<?=$i?>]" value="<?=$ex['excel_tamount']?>">
						<?=number_format($ex['excel_tamount'])?>
					</td>
				</tr>
<?
	}
?>
					<tfoot class="table-light font-weight-bold">
						<tr>
							<td></td>
							<td><?=count($excels)?></td>
							<td colspan="5"></td>
							<td class="text-right"><?=number_format($sum_excel_qty)?></td>
							<td></td>
							<td class="text-right"><?=number_format($sum_excel_amount)?></td>
							<td class="text-right"><?=number_format($sum_excel_tax)?></td>
							<td class="text-right"><?=number_format($sum_excel_tamount)?></td>
						</tr>
					</tfoot>
				</table>
			</form>
		</div>
	</div>
</div>

<div id="footer-control" class="fixed-bottom bg-light border-top" style="display:none;">
	<div class="container-xxl p-3">
		<div class="row">
			<div class="col">
				<button type="submit" class="btn btn-primary btn-block" id="controlExcelInputButton" form="excelControlForm" formaction="<?=BASE_URL?>/input/ajax_excel_input"><i class="feather icon-arrow-right-circle"></i> 선택한 것을 입고</button>
			</div>
		</div>
	</div>
</div>

<!-- 입고 엑셀 추가 Modal -->
<div class="modal fade" id="excelModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="excelModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="excelModalLabel">입고항목 엑셀 추가</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?=BASE_URL?>/input/excel" id="excelForm" class="needs-validation" novalidate>
					<div class="mb-3">
						<label for="excel_time" class="form-label">입고일자<span class="text-danger">*</span></label>
						<input type="date" class="form-control" name="input_time" id="excel_time" autocomplete="off" value="<?=date('Y-m-d')?>" required>
					</div>
					<div class="mb-3">
						<label for="excel_content" class="form-label">엑셀내용<span class="text-danger">*</span></label>
						<textarea class="form-control form-control-sm" name="excel_content" id="excel_content" rows="10" required></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
				<button type="submit" class="btn btn-primary" form="excelForm" id="excelConfirm"><i class="feather icon-arrow-right-circle"></i> 검토하기</button>
			</div>
		</div>
	</div>
</div>

<script>
$(function(){
	'use strict';

	$('.select-all').click(function(){
		$('.select-each').trigger('click');
	});

	$('.select-each').click(function(){
		var targets = $('.select-each:checked');
		if(targets.length > 0){
			$('#footer-control').show();
		}else{
			$('#footer-control').hide();
		}
	});

	var target_idx = $('.select-each');
	var checked_idx = $('.select-each:checked');
	if(checked_idx.length > 0 && target_idx.length == checked_idx.length){
		$('.select-all').attr('checked', 'checked');
		$('#footer-control').show();
	}else if(checked_idx.length > 0){
		$('#footer-control').show();
	}

	// 엑셀 입고항목 추가
	$('#controlExcelInputButton').click(function(event){
		event.preventDefault();
		$('#now-loading').show();

		var form_id = $(this).attr('form');
		var form = $('#'+form_id)[0];
		var action = $(this).attr("formaction");
		var form_data = new FormData(form);

		if(form.checkValidity() === false){
			Swal.fire({icon: 'error', title: '실패', text: '빠진 내용이 없는지 확인하여 주십시오.', timer: 1500});
			form.classList.add('was-validated');
			$('#now-loading').hide();
			return false;
		}

		var request = $.ajax({url: action, method: 'POST', data: form_data, processData: false, contentType: false, dataType: "json"});
		request.done(function(data){
			$('#now-loading').hide();
			if(data.result == 'true'){
				Swal.fire({icon: 'success', title: '성공', text: '입고항목이 추가되었습니다.', timer: 1500})
					.then(function(){
						location.href = '<?=BASE_URL?>/input';
					});
			}else{
				Swal.fire({icon: 'error', title: '실패', text: data.message, timer: 1500});
			}
		});
		request.fail(function(){
			$('#now-loading').hide();
			Swal.fire({icon: 'error', title: '실패', text: '요청이 실패하였습니다. 관리자에게 문의해주세요.', timer: 1500});
		});
	});
	
	// 엑셀 추가
	$('#excelConfirm').click(function(event){
		event.preventDefault();
		$('#now-loading').show();

		var form_id = $(this).attr('form');
		var form = $('#'+form_id)[0];
		var form_data = new FormData(form);

		if(form.checkValidity() === false){
			Swal.fire({icon: 'error', title: '실패', text: '빠진 내용이 없는지 확인하여 주십시오.', timer: 1500});
			form.classList.add('was-validated');
			$('#now-loading').hide();
			return false;
		}

		$(form).trigger('submit');
	});

});
</script>