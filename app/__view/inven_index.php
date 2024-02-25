<div class="container-xxl py-3" style="height:calc(100vh - 3.5rem);">
	<div class="d-flex align-items-center justify-content-between mb-3">
		<h2 class="h4 font-weight-bold mb-0"><i class="feather icon-monitor"></i> 재고표</h2>
		<div>
			<a href="<?=BASE_URL?>/inven/file_inventory?start=<?=date('Y-m-d', $start)?>&end=<?=date('Y-m-d', $end)?>&search_word=<?=$search_word?>" class="btn btn-outline-dark"><i class="feather icon-download-cloud"></i> 엑셀</a>
			<a href="<?=BASE_URL?>/inven/print_inventory?start=<?=date('Y-m-d', $start)?>&end=<?=date('Y-m-d', $end)?>&search_word=<?=$search_word?>" class="printdisplay btn btn-outline-dark"><i class="feather icon-printer"></i> 인쇄</a>
		</div>
	</div>
	<div id="search">
		<form method="get" class="card card-body shadow-sm bg-light pb-0 mb-3">
			<div class="row mb-3">
				<label for="start" class="col-md-2 col-form-label">검색기간</label>
				<div class="col-md-6">
					<div class="input-group">
						<input type="date" name="start" id="start" class="form-control" value="<?=date('Y-m-d', $start)?>">
						<input type="date" name="end" id="end" class="form-control" value="<?=date('Y-m-d', $end)?>">
					</div>
				</div>
			</div>
			<div class="row mb-3">
				<label for="search_word" class="col-md-2 col-form-label">검색어</label>
				<div class="col-md-6">
					<input type="text" name="search_word" id="search_word" class="form-control" value="<?=$search_word?>">
				</div>
				<div class="col-md"><button type="submit" class="btn btn-block btn-primary">검색</button></div>
			</div>
		</form>
	</div>
	<div class="row">
		<div class="col-lg">
			<form method="post" id="invenControlForm" class="table-responsive scrollbar-macosx">
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
							<th>제품코드</th>
							<th>제품명</th>
							<th>품목코드</th>
							<th>컬러</th>
							<th>사이즈</th>
							<th>이월<br>재고</th>
							<th>입고</th>
							<th>출고</th>
							<th>불량</th>
							<th>현재<br>재고</th>
							<th>입고<br>예정</th>
							<th>재고금액</th>
						</tr>
					</thead>
					<tbody>
<?
	$sum_before_qty = 0;
	$sum_input_qty = 0;
	$sum_output_qty = 0;
	$sum_defect_qty = 0;
	$sum_stock_qty = 0;
	$sum_po_qty = 0;
	if($invens){ // 입고항목이 있으면
		foreach($invens as $i => $pd){
			$sum_before_qty+= $pd['basic'];
			$sum_input_qty+= $pd['input'];
			$sum_output_qty+= $pd['output'];
			$sum_defect_qty+= $pd['defect'];
			$sum_stock_qty+= $pd['stock'];
			$sum_po_qty+= $pd['po'];
?>
						<tr>
							<td>
								<div class="form-check mb-0 ml-1">
									<input name="product_idx[]" class="form-check-input select-each" type="checkbox" id="select-each<?=$pd['idx']?>" value="<?=$pd['idx']?>">
									<label class="form-check-label" for="select-each<?=$pd['idx']?>">
								</div>
							</td>
							<td class="position-relative text-nowrap text-center"><?=$pd['idx']?></td>
							<td class="position-relative"><?=$pd['category_code']?></td>
							<td class="position-relative"><?=$pd['category_name']?></td>
							<td class="position-relative"><?=$pd['product_code']?></td>
							<td class="position-relative"><?=$pd['product_color']?></td>
							<td class="position-relative"><?=$pd['product_size']?></td>
							<td class="position-relative text-right"><?=number_format($pd['basic'])?></td>
							<td class="position-relative text-right"><a href="#" class="input_history text-decoration-none" data-product-idx="<?=$pd['idx']?>"><?=number_format($pd['input'])?></a></td>
							<td class="position-relative text-right"><a href="#" class="output_history text-decoration-none" data-product-idx="<?=$pd['idx']?>"><?=number_format($pd['output'])?></a></td>
							<td class="position-relative text-right"><a href="#" class="defect_history text-decoration-none" data-product-idx="<?=$pd['idx']?>"><?=number_format($pd['defect'])?></a></td>
							<td class="position-relative text-right font-weight-bold"><?=number_format($pd['stock'])?></td>
							<td class="position-relative text-right"><?=number_format($pd['po'])?></td>
							<td class="position-relative text-right"><?=number_format($pd['inven_amount'])?></td>
						</tr>
<?
		}
	}else{ // 입고항목이 없으면
?>
						<tr>
							<td colspan="13" class="py-5 text-danger text-center">등록된 제품코드가 없습니다.</td>
						</tr>
<?
	}
?>
					<tfoot class="table-light font-weight-bold">
						<tr>
							<td></td>
							<td><?=$total?></td>
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
			</form>
		</div>
	</div>
</div>

<div id="footer-control" class="fixed-bottom bg-light border-top" style="display:none;">
	<div class="container-xxl p-3">
		<div class="row">
			<div class="col">
				<button type="submit" class="btn btn-danger btn-block" id="controlInvenDeleteButton" form="inputControlForm" formaction="<?=BASE_URL?>/inven/ajax_delete_inputs"><i class="feather icon-trash"></i> 선택한 것을 삭제</button>
			</div>
		</div>
	</div>
</div>

<!-- 상세보기 Modal -->
<div class="modal fade" id="historyModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content" id="historyModalContent">
		</div>
	</div>
</div>

<script>
$(function(){
	'use strict';

	$('.input_history').click(function(){
		var product_idx = $(this).data('productIdx');
		$("#historyModalContent").load("<?=BASE_URL?>/inven/ajax_load_input_history", {'product_idx' : product_idx, 'start' : <?=$start?>, 'end' : <?=$end?>});
		$('#historyModal').modal('show');
	});

	$('.output_history').click(function(){
		var product_idx = $(this).data('productIdx');
		$("#historyModalContent").load("<?=BASE_URL?>/inven/ajax_load_output_history", {'product_idx' : product_idx, 'start' : <?=$start?>, 'end' : <?=$end?>});
		$('#historyModal').modal('show');
	});

	$('.defect_history').click(function(){
		var product_idx = $(this).data('productIdx');
		$("#historyModalContent").load("<?=BASE_URL?>/inven/ajax_load_defect_history", {'product_idx' : product_idx, 'start' : <?=$start?>, 'end' : <?=$end?>});
		$('#historyModal').modal('show');
	});

	$('#input_time').trigger('focus');

	$('.select-all').click(function(){
		if($(this).prop('checked')){
			$('#footer-control').show();
		}else{
			$('#footer-control').hide();
		}
	});

	$('.select-each').click(function(){
		var targets = $('.select-each:checked');
		if(targets.length > 0){
			$('#footer-control').show();
		}else{
			$('#footer-control').hide();
		}
	});

	$('#category_idx').change(function(){
		var category_idx = $(this).val();

		if(category_idx){

			// 초기화
			$('#product_color').html('');
			$('#product_size').html('');

			$.inputst("<?=BASE_URL?>/input/ajax_get_category_colors", {'category_idx' : category_idx}, function(data){
				if(data.result == 'true' && data.datas.length > 0){
					$("#product_color").append('<option value="">선택</option>');
					for(var i = 0; i <= data.datas.length; i++){
						if(data.datas[i]) $("#product_color").append('<option value="' + data.datas[i].product_color + '">' + data.datas[i].product_color + '</option>');
					}
					//$("#product_color").selectpicker('refresh');
				}else{
					$("#product_color").append('<option value="">선택</option>');
					//$("#product_color").selectpicker('refresh');
				}
			},"json");
		}
	});

	$('#product_color').change(function(){
		var category_idx = $('#category_idx').val();
		var product_color = $(this).val();

		if(product_color){

			// 초기화
			$('#product_size').html('');

			$.inputst("<?=BASE_URL?>/input/ajax_get_category_color_sizes", {'category_idx' : category_idx, 'product_color' : product_color}, function(data){
				if(data.result == 'true' && data.datas.length > 0){
					$("#product_size").append('<option value="">선택</option>');
					for(var i = 0; i <= data.datas.length; i++){
						if(data.datas[i]) $("#product_size").append('<option value="' + data.datas[i].idx + '">' + data.datas[i].product_size + '</option>');
					}
					//$("#product_color").selectpicker('refresh');
				}else{
					$("#product_size").append('<option value="">선택</option>');
					//$("#product_color").selectpicker('refresh');
				}
			},"json");
		}
	});

	function tkcalc(qty, price, amount = null, tax = null){
		qty = parseInt(qty);
		price = parseInt(price);
		amount = parseInt(amount);
		tax = parseInt(tax);
		var tamount = 0;

		if(!qty) qty = 0;
		if(!price) price = 0;
		if(!amount) amount = 0;
		if(!tax) tax = 0;
		
		if(!amount) amount = price * qty;
		if(!tax) tax = amount * 0.1;
		tamount = amount + tax;

		$('#input_qty').val(qty);
		$('#input_price').val(price);
		$('#input_amount').val(amount);
		$('#input_tax').val(tax);
		$('#input_tamount').val(tamount);
	}

	$('#input_qty, #input_price').change(function(){
		var qty = $('#input_qty').val();
		var price = $('#input_price').val();
		tkcalc(qty,price);
	});

	$('#input_amount').change(function(){
		var qty = $('#input_qty').val();
		var price = $('#input_price').val();
		var amount = $('#input_amount').val();
		tkcalc(qty,price,amount);
	});

	$('#input_tax').change(function(){
		var qty = $('#input_qty').val();
		var price = $('#input_price').val();
		var amount = $('#input_amount').val();
		var tax = $('#input_tax').val();
		tkcalc(qty,price,amount,tax);
	});

	// 입고항목 추가
	$('#inputAddConfirm').click(function(event){
		event.preventDefault();
		$('#now-loading').show();

		var form = $(this).closest('form')[0];
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
						location.href = '<?=BASE_URL?>/input/index/' + data.idx + '?start=<?=@$_GET['start']?>&end=<?=@$_GET['end']?>&search_word=<?=@$_GET['search_word']?>&page=<?=@$_GET['page']?>';
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

	// 입고항목 수정
	$('#inputEditConfirm').click(function(event){
		event.preventDefault();
		$('#now-loading').show();

		var form = $(this).closest('form')[0];
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
				Swal.fire({icon: 'success', title: '성공', text: '입고항목이 수정되었습니다.', timer: 1500})
					.then(function(){
						location.reload();
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

	$('#controlInputDeleteButton').click(function(event){
		event.preventDefault();

		var form_id = $(this).attr('form');
		var form = $('#'+form_id)[0];
		var action = $(this).attr("formaction");
		var method = $(form).attr("method");
		var formData = new FormData(form);

		var targets = $(form).find('.select-each:checked');
		if(targets.length < 1){
			const Toast = Swal.mixin({toast: true, position: 'center', showConfirmButton: false, timer: 1500});
			Toast.fire({icon: 'error', title: '선택된 항목이 없습니다.'});
			return false;
		}else{
			Swal.fire({
				icon: 'warning',
				title: '삭제',
				text: '정말 삭제하시겠습니까?',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: '예, 삭제합니다!',
				cancelButtonText: '아니오'
			}).then(function(result){
				if (result.value){
					$('#now-loading').show();
					var request = $.ajax({url: action, type: method, data: formData, processData: false, contentType: false, dataType: "json"});
					request.done(function(data){
						$('#now-loading').hide();
						if(data.result == 'true'){
							Swal.fire({icon: 'success', title: '성공', text: '입고항목이 삭제되었습니다.', timer: 1500})
								.then(function(){
									location.reload();
								});
						}else{
							Swal.fire({icon: 'error', title: '실패', text: data.message, timer: 1500});
						}
					});
					request.fail(function(){
						$('#now-loading').hide();
						Swal.fire({icon: 'error', title: '실패', text: '요청이 실패하였습니다. 관리자에게 문의해주세요.', timer: 1500});
					});
				}
			});
		}
	});

});
</script>