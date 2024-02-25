<div class="container-xxl py-3" style="height:calc(100vh - 3.5rem);">
	<div class="d-flex align-items-center justify-content-between mb-3">
		<h2 class="h4 font-weight-bold mb-0"><i class="feather icon-truck"></i> 출고</h2>
		<div>
			<a href="<?=BASE_URL?>/output" class="btn btn-outline-dark" title="검색 취소"><i class="feather icon-rotate-ccw"></i></a>
			<button type="button" class="btn btn-outline-dark" data-toggle="collapse" data-target="#search" aria-expanded="false" aria-controls="collapseExample"><i class="feather icon-search"></i></button>
			<button type="button" class="btn btn-outline-success" title="엑셀로 등록하기" data-toggle="modal" data-target="#excelModal"><i class="feather icon-file-plus"></i> 엑셀</button>
		</div>
	</div>
	<div class="collapse" id="search">
		<form method="get" class="card card-body shadow-sm bg-light pb-0 mb-3">
			<div class="row mb-3">
				<label for="start" class="col-md-2 col-form-label">출고일자</label>
				<div class="col-md-6">
					<div class="input-group">
						<input type="date" name="start" id="start" class="form-control" value="<?=@$_GET['start']?>">
						<input type="date" name="end" id="end" class="form-control" value="<?=@$_GET['end']?>">
					</div>
				</div>
			</div>
			<div class="row mb-3">
				<label for="search_word" class="col-md-2 col-form-label">검색어</label>
				<div class="col-md-6">
					<input type="text" name="search_word" id="search_word" class="form-control" value="<?=@$_GET['search_word']?>">
				</div>
				<div class="col-md"><button type="submit" class="btn btn-block btn-primary">검색</button></div>
			</div>
		</form>
	</div>
	<div class="row">
		<div class="col-lg">
			<form method="post" id="outputControlForm" class="table-responsive scrollbar-macosx">
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
							<th>출고일자</th>
							<th>제품코드</th>
							<th>제품명</th>
							<th>품목코드</th>
							<th>컬러</th>
							<th>사이즈</th>
							<th>수량</th>
							<th>단가</th>
							<th>금액</th>
							<th>비고</th>
						</tr>
					</thead>
					<tbody>
<?
	if($outputs){ // 출고항목이 있으면
		foreach($outputs as $i => $op){
			$link = BASE_URL.'/output/index/'.$op['idx'].'?start='.@$_GET['start'].'&end='.@$_GET['end'].'&search_word='.@$_GET['search_word'].'&page='.@$_GET['page'];
?>
						<tr<?if($output && $output['idx'] == $op['idx']){?> class="table-warning"<?}?>>
							<td>
								<div class="form-check mb-0 ml-1">
									<input name="output_idx[]" class="form-check-input select-each" type="checkbox" id="select-each<?=$op['idx']?>" value="<?=$op['idx']?>">
									<label class="form-check-label" for="select-each<?=$op['idx']?>">
								</div>
							</td>
							<td class="position-relative text-nowrap text-center"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=$op['idx']?></a></td>
							<td class="position-relative"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=date('Y-m-d', $op['output_time'])?></a></td>
							<td class="position-relative"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=$op['category_code']?></a></td>
							<td class="position-relative"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=$op['category_name']?></a></td>
							<td class="position-relative"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=$op['product_code']?></a></td>
							<td class="position-relative"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=$op['product_color']?></a></td>
							<td class="position-relative"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=$op['product_size']?></a></td>
							<td class="position-relative text-right"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=number_format($op['output_qty'])?></a></td>
							<td class="position-relative text-right"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=number_format($op['output_price'])?></a></td>
							<td class="position-relative text-right"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=number_format($op['output_amount'])?></a></td>
							<td class="position-relative"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=nl2br($op['output_memo'])?></a></td>
						</tr>
<?
		}
	}else{ // 출고항목이 없으면
?>
						<tr>
							<td colspan="12" class="py-5 text-danger text-center">출고항목이 없습니다.</td>
						</tr>
<?
	}
?>
					<tfoot class="table-light font-weight-bold">
						<tr>
							<td></td>
							<td><?=$total?></td>
							<td colspan="6"></td>
							<td class="text-right"><?=number_format($sum_output_qty)?></td>
							<td></td>
							<td class="text-right"><?=number_format($sum_output_amount)?></td>
							<td></td>
						</tr>
					</tfoot>
				</table>
			</form>

			<nav class="">
				<ul class="pagination pagination-sm justify-content-center">
<? // PAGING START
echo '<li class="page-item"><a href="?start='.@$_GET['start'].'&end='.@$_GET['end'].'&search_word='.@$_GET['search_word'].'&page=1" class="page-link"><i class="feather icon-chevron-left"></i></a></li>'; // 첫 페이지 
for($i=0;$i<$page_half;$i++){
	$j = $page - $page_half + $i;
	if($j > 0){ 
		echo '<li class="page-item"><a href="?start='.@$_GET['start'].'&end='.@$_GET['end'].'&search_word='.@$_GET['search_word'].'&page='.$j.'" class="page-link">'.$j.'</a>'; // 현재 페이지의 앞페이지들
	}
} 

echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>'; // 현재 페이지 

for($i=0;$i<$page_half;$i++){
	$j = $page + $i + 1;
	if($j <= $page_total){
		echo '<li class="page-item"><a href="?start='.@$_GET['start'].'&end='.@$_GET['end'].'&search_word='.@$_GET['search_word'].'&page='.$j.'" class="page-link">'.$j.'</a></li>'; // 현재 페이지의 뒷페이지들
	}
}

echo '<li class="page-item"><a href="?start='.@$_GET['start'].'&end='.@$_GET['end'].'&search_word='.@$_GET['search_word'].'&page='.$page_total.'" class="page-link"><i class="feather icon-chevron-right"></i></a></li>'; // 끝 페이지
// PAGING END
?>
				</ul>
			</nav>
		</div>
		<div class="col-xl-3 col-lg-4" id="outputInputArea">
			<form method="post" id="outputForm" class="alert <?if($output){?>alert-warning<?}else{?>alert-primary<?}?> needs-validation" novalidate>
<?if($output){?>
				<input type="hidden" name="output_idx" value="<?=$output['idx']?>">
<?}?>
				<div class="d-flex align-items-end justify-content-between">
					<h3 class="alert-heading h5 font-weight-bold mb-0"><?if($output){?>[<?=$output['idx']?>] 수정<?}else{?>출고 등록<?}?></h3>
<?if($output){?>
					<a href="<?=BASE_URL?>/output?start=<?=@$_GET['start']?>&end=<?=@$_GET['end']?>&search_word=<?=@$_GET['search_word']?>&page=<?=@$_GET['page']?>" class="text-decoration-none text-reset"><i class="feather icon-x"></i></a>
<?}?>
				</div>
				<hr>
				<div class="row mb-2">
					<label for="output_time" class="col-4 col-form-label">출고일자</label>
					<div class="col-8">
						<input type="date" name="output_time" id="output_time" class="form-control" value="<?if($output){?><?=date('Y-m-d', $output['output_time'])?><?}else{?><?=date('Y-m-d')?><?}?>" required>
					</div>
				</div>
				<div class="row mb-2">
					<label for="category_idx" class="col-4 col-form-label">제품코드</label>
					<div class="col-8">
						<select name="category_idx" id="category_idx" class="form-select" required>
							<option value="">선택</option>
<?if($categorys){foreach($categorys as $ct){?>
							<option value="<?=$ct['idx']?>"<?if($output && $output['category_idx'] == $ct['idx']){?> selected<?}?>><?=$ct['category_code']?> : <?=$ct['category_name']?></option>
<?}}?>
						</select>
					</div>
				</div>
				<div class="row mb-2">
					<label for="product_color" class="col-4 col-form-label">컬러</label>
					<div class="col-8">
						<select name="product_color" id="product_color" class="form-select" required>
<?if($output){foreach($product_colors as $pd){?>
							<option value="<?=$pd['product_color']?>"<?if($output['product_color'] == $pd['product_color']){?> selected<?}?>><?=$pd['product_color']?></option>
<?}}?>
						</select>
					</div>
				</div>
				<div class="row mb-2">
					<label for="product_size" class="col-4 col-form-label">사이즈</label>
					<div class="col-8">
						<select name="product_size" id="product_size" class="form-select" required>
<?if($output){foreach($product_sizes as $pd){?>
							<option value="<?=$pd['idx']?>"<?if($output['product_size'] == $pd['product_size']){?> selected<?}?>><?=$pd['product_size']?></option>
<?}}?>
						</select>
					</div>
				</div>
				<div class="row mb-2">
					<label for="output_qty" class="col-4 col-form-label">수량</label>
					<div class="col-8">
						<input type="text" name="output_qty" id="output_qty" class="form-control number-format"<?if($output){?> value="<?=number_format($output['output_qty'])?>"<?}?> required>
					</div>
				</div>
				<div class="row mb-2">
					<label for="output_price" class="col-4 col-form-label">단가</label>
					<div class="col-8">
						<input type="text" name="output_price" id="output_price" class="form-control number-format"<?if($output){?> value="<?=number_format($output['output_price'])?>"<?}?> required>
					</div>
				</div>
				<div class="row mb-2">
					<label for="output_amount" class="col-4 col-form-label">금액</label>
					<div class="col-8">
						<input type="text" name="output_amount" id="output_amount" class="form-control number-format"<?if($output){?> value="<?=number_format($output['output_amount'])?>"<?}?> required>
					</div>
				</div>
				<div class="row mb-2">
					<label for="output_tax" class="col-4 col-form-label">세액</label>
					<div class="col-8">
						<input type="text" name="output_tax" id="output_tax" class="form-control number-format"<?if($output){?> value="<?=number_format($output['output_tax'])?>"<?}?> required>
					</div>
				</div>
				<div class="row mb-2">
					<label for="output_tamount" class="col-4 col-form-label">합계</label>
					<div class="col-8">
						<input type="text" name="output_tamount" id="output_tamount" class="form-control number-format"<?if($output){?> value="<?=number_format($output['output_tamount'])?>"<?}?> readonly required>
					</div>
				</div>
				<div class="mb-2">
					<label for="output_memo" class="sr-only">비고</label>
					<textarea name="output_memo" id="output_memo" class="form-control form-control-sm" rows="2" placeholder="비고"><?if($output){?> <?=$output['output_memo']?><?}?></textarea>
				</div>
				<div class="row g-1">
<?if($output){?>
					<div class="col">
						<button type="submit" class="btn btn-warning btn-block" formaction="<?=BASE_URL?>/output/ajax_edit_output" id="outputEditConfirm">수정</button>
					</div>
<?}?>
					<div class="col">
						<button type="submit" class="btn btn-primary btn-block" formaction="<?=BASE_URL?>/output/ajax_add_output" id="outputAddConfirm">추가</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<div id="footer-control" class="fixed-bottom bg-light border-top" style="display:none;">
	<div class="container-xxl p-3">
		<div class="row">
			<div class="col">
				<button type="submit" class="btn btn-danger btn-block" id="controlOutputDeleteButton" form="outputControlForm" formaction="<?=BASE_URL?>/output/ajax_delete_outputs"><i class="feather icon-trash"></i> 선택한 것을 삭제</button>
			</div>
		</div>
	</div>
</div>

<!-- 출고 엑셀 추가 Modal -->
<div class="modal fade" id="excelModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="excelModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="excelModalLabel">출고항목 엑셀 추가</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?=BASE_URL?>/output/excel" id="excelForm" class="needs-validation" novalidate>
					<div class="mb-3">
						<label for="excel_time" class="form-label">출고일자<span class="text-danger">*</span></label>
						<input type="date" class="form-control" name="output_time" id="excel_time" autocomplete="off" value="<?=date('Y-m-d')?>" required>
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

	$('#output_time').trigger('focus');

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

	$('#category_idx').change(function(){
		var category_idx = $(this).val();

		if(category_idx){

			// 초기화
			$('#product_color').html('');
			$('#product_size').html('');

			$.post("<?=BASE_URL?>/output/ajax_get_category_colors", {'category_idx' : category_idx}, function(data){
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

			$.post("<?=BASE_URL?>/output/ajax_get_category_color_sizes", {'category_idx' : category_idx, 'product_color' : product_color}, function(data){
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

		$('#output_qty').val(qty);
		$('#output_price').val(price);
		$('#output_amount').val(amount);
		$('#output_tax').val(tax);
		$('#output_tamount').val(tamount);
	}

	$('#output_qty, #output_price').change(function(){
		var qty = $('#output_qty').val();
		var price = $('#output_price').val();
		tkcalc(qty,price);
	});

	$('#output_amount').change(function(){
		var qty = $('#output_qty').val();
		var price = $('#output_price').val();
		var amount = $('#output_amount').val();
		tkcalc(qty,price,amount);
	});

	$('#output_tax').change(function(){
		var qty = $('#output_qty').val();
		var price = $('#output_price').val();
		var amount = $('#output_amount').val();
		var tax = $('#output_tax').val();
		tkcalc(qty,price,amount,tax);
	});

	// 출고항목 추가
	$('#outputAddConfirm').click(function(event){
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
				Swal.fire({icon: 'success', title: '성공', text: '출고항목이 추가되었습니다.', timer: 1500})
					.then(function(){
						location.href = '<?=BASE_URL?>/output/index/' + data.idx + '?start=<?=@$_GET['start']?>&end=<?=@$_GET['end']?>&search_word=<?=@$_GET['search_word']?>&page=<?=@$_GET['page']?>';
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

	// 출고항목 수정
	$('#outputEditConfirm').click(function(event){
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
				Swal.fire({icon: 'success', title: '성공', text: '출고항목이 수정되었습니다.', timer: 1500})
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

	$('#controlOutputDeleteButton').click(function(event){
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
							Swal.fire({icon: 'success', title: '성공', text: '출고항목이 삭제되었습니다.', timer: 1500})
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