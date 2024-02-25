<div class="container-xxl py-3" style="height:calc(100vh - 3.5rem);">
	<div class="d-flex align-items-center justify-content-between mb-3">
		<h2 class="h4 font-weight-bold mb-0"><i class="feather icon-trash-2"></i> 불량</h2>
		<div>
			<button type="button" class="btn btn-outline-dark" data-toggle="collapse" data-target="#search" aria-expanded="false" aria-controls="collapseExample"><i class="feather icon-search"></i></button>
		</div>
	</div>
	<div class="collapse" id="search">
		<form method="get" class="card card-body shadow-sm bg-light pb-0 mb-3">
			<div class="row mb-3">
				<label for="start" class="col-md-2 col-form-label">불량일자</label>
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
			<form method="post" id="defectControlForm" class="table-responsive scrollbar-macosx">
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
							<th>불량일자</th>
							<th>제품코드</th>
							<th>제품명</th>
							<th>품목코드</th>
							<th>컬러</th>
							<th>사이즈</th>
							<th>수량</th>
							<th>단가</th>
							<th>금액</th>
							<th>불량사유</th>
						</tr>
					</thead>
					<tbody>
<?
	if($defects){ // 불량항목이 있으면
		foreach($defects as $i => $df){
			$link = BASE_URL.'/defect/index/'.$df['idx'].'?start='.@$_GET['start'].'&end='.@$_GET['end'].'&search_word='.@$_GET['search_word'].'&page='.@$_GET['page'];
?>
						<tr<?if($defect && $defect['idx'] == $df['idx']){?> class="table-warning"<?}?>>
							<td>
								<div class="form-check mb-0 ml-1">
									<input name="defect_idx[]" class="form-check-input select-each" type="checkbox" id="select-each<?=$df['idx']?>" value="<?=$df['idx']?>">
									<label class="form-check-label" for="select-each<?=$df['idx']?>">
								</div>
							</td>
							<td class="position-relative text-nowrap text-center"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=$df['idx']?></a></td>
							<td class="position-relative"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=date('Y-m-d', $df['defect_time'])?></a></td>
							<td class="position-relative"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=$df['category_code']?></a></td>
							<td class="position-relative"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=$df['category_name']?></a></td>
							<td class="position-relative"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=$df['product_code']?></a></td>
							<td class="position-relative"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=$df['product_color']?></a></td>
							<td class="position-relative"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=$df['product_size']?></a></td>
							<td class="position-relative text-right"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=number_format($df['defect_qty'])?></a></td>
							<td class="position-relative text-right"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=number_format($df['defect_price'])?></a></td>
							<td class="position-relative text-right"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=number_format($df['defect_amount'])?></a></td>
							<td class="position-relative"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=nl2br($df['defect_memo'])?></a></td>
						</tr>
<?
		}
	}else{ // 불량항목이 없으면
?>
						<tr>
							<td colspan="12" class="py-5 text-danger text-center">불량항목이 없습니다.</td>
						</tr>
<?
	}
?>
					<tfoot class="table-light font-weight-bold">
						<tr>
							<td></td>
							<td><?=$total?></td>
							<td colspan="6"></td>
							<td class="text-right"><?=number_format($sum_defect_qty)?></td>
							<td></td>
							<td class="text-right"><?=number_format($sum_defect_amount)?></td>
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
		<div class="col-xl-3 col-lg-4" id="defectInputArea">
			<form method="post" id="defectForm" class="alert <?if($defect){?>alert-warning<?}else{?>alert-primary<?}?> needs-validation" novalidate>
<?if($defect){?>
				<input type="hidden" name="defect_idx" value="<?=$defect['idx']?>">
<?}?>
				<div class="d-flex align-items-end justify-content-between">
					<h3 class="alert-heading h5 font-weight-bold mb-0"><?if($defect){?>[<?=$defect['idx']?>] 수정<?}else{?>불량 등록<?}?></h3>
<?if($defect){?>
					<a href="<?=BASE_URL?>/defect?start=<?=@$_GET['start']?>&end=<?=@$_GET['end']?>&search_word=<?=@$_GET['search_word']?>&page=<?=@$_GET['page']?>" class="text-decoration-none text-reset"><i class="feather icon-x"></i></a>
<?}?>
				</div>
				<hr>
				<div class="row mb-2">
					<label for="defect_time" class="col-4 col-form-label">불량일자</label>
					<div class="col-8">
						<input type="date" name="defect_time" id="defect_time" class="form-control" value="<?if($defect){?><?=date('Y-m-d', $defect['defect_time'])?><?}else{?><?=date('Y-m-d')?><?}?>" required>
					</div>
				</div>
				<div class="row mb-2">
					<label for="category_idx" class="col-4 col-form-label">제품코드</label>
					<div class="col-8">
						<select name="category_idx" id="category_idx" class="form-select" required>
							<option value="">선택</option>
<?if($categorys){foreach($categorys as $ct){?>
							<option value="<?=$ct['idx']?>"<?if($defect && $defect['category_idx'] == $ct['idx']){?> selected<?}?>><?=$ct['category_code']?> : <?=$ct['category_name']?></option>
<?}}?>
						</select>
					</div>
				</div>
				<div class="row mb-2">
					<label for="product_color" class="col-4 col-form-label">컬러</label>
					<div class="col-8">
						<select name="product_color" id="product_color" class="form-select" required>
<?if($defect){foreach($product_colors as $pd){?>
							<option value="<?=$pd['product_color']?>"<?if($defect['product_color'] == $pd['product_color']){?> selected<?}?>><?=$pd['product_color']?></option>
<?}}?>
						</select>
					</div>
				</div>
				<div class="row mb-2">
					<label for="product_size" class="col-4 col-form-label">사이즈</label>
					<div class="col-8">
						<select name="product_size" id="product_size" class="form-select" required>
<?if($defect){foreach($product_sizes as $pd){?>
							<option value="<?=$pd['idx']?>"<?if($defect['product_size'] == $pd['product_size']){?> selected<?}?>><?=$pd['product_size']?></option>
<?}}?>
						</select>
					</div>
				</div>
				<div class="row mb-2">
					<label for="defect_qty" class="col-4 col-form-label">수량</label>
					<div class="col-8">
						<input type="text" name="defect_qty" id="defect_qty" class="form-control number-format"<?if($defect){?> value="<?=number_format($defect['defect_qty'])?>"<?}?> required>
					</div>
				</div>
				<div class="row mb-2">
					<label for="defect_price" class="col-4 col-form-label">단가</label>
					<div class="col-8">
						<input type="text" name="defect_price" id="defect_price" class="form-control number-format"<?if($defect){?> value="<?=number_format($defect['defect_price'])?>"<?}?> required>
					</div>
				</div>
				<div class="row mb-2">
					<label for="defect_amount" class="col-4 col-form-label">금액</label>
					<div class="col-8">
						<input type="text" name="defect_amount" id="defect_amount" class="form-control number-format"<?if($defect){?> value="<?=number_format($defect['defect_amount'])?>"<?}?> required>
					</div>
				</div>
				<div class="row mb-2">
					<label for="defect_tax" class="col-4 col-form-label">세액</label>
					<div class="col-8">
						<input type="text" name="defect_tax" id="defect_tax" class="form-control number-format"<?if($defect){?> value="<?=number_format($defect['defect_tax'])?>"<?}?> required>
					</div>
				</div>
				<div class="row mb-2">
					<label for="defect_tamount" class="col-4 col-form-label">합계</label>
					<div class="col-8">
						<input type="text" name="defect_tamount" id="defect_tamount" class="form-control number-format"<?if($defect){?> value="<?=number_format($defect['defect_tamount'])?>"<?}?> readonly required>
					</div>
				</div>
				<div class="mb-2">
					<label for="defect_memo" class="sr-only">불량사유</label>
					<textarea name="defect_memo" id="defect_memo" class="form-control form-control-sm" rows="2" placeholder="불량사유"><?if($defect){?> <?=$defect['defect_memo']?><?}?></textarea>
				</div>
				<div class="row g-1">
<?if($defect){?>
					<div class="col">
						<button type="submit" class="btn btn-warning btn-block" formaction="<?=BASE_URL?>/defect/ajax_edit_defect" id="defectEditConfirm">수정</button>
					</div>
<?}?>
					<div class="col">
						<button type="submit" class="btn btn-primary btn-block" formaction="<?=BASE_URL?>/defect/ajax_add_defect" id="defectAddConfirm">추가</button>
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
				<button type="submit" class="btn btn-danger btn-block" id="controlDefectDeleteButton" form="defectControlForm" formaction="<?=BASE_URL?>/defect/ajax_delete_defects"><i class="feather icon-trash"></i> 선택한 것을 삭제</button>
			</div>
			<div class="col">
				<button type="submit" class="btn btn-primary btn-block" id="controlDefectOutputButton" form="defectControlForm" formaction="<?=BASE_URL?>/defect/ajax_output_defects"><i class="feather icon-truck"></i> 출고</button>
			</div>
		</div>
	</div>
</div>

<script>
$(function(){
	'use strict';

	$('#defect_time').trigger('focus');

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

			$.post("<?=BASE_URL?>/defect/ajax_get_category_colors", {'category_idx' : category_idx}, function(data){
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

			$.post("<?=BASE_URL?>/defect/ajax_get_category_color_sizes", {'category_idx' : category_idx, 'product_color' : product_color}, function(data){
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

		$('#defect_qty').val(qty);
		$('#defect_price').val(price);
		$('#defect_amount').val(amount);
		$('#defect_tax').val(tax);
		$('#defect_tamount').val(tamount);
	}

	$('#defect_qty, #defect_price').change(function(){
		var qty = $('#defect_qty').val();
		var price = $('#defect_price').val();
		tkcalc(qty,price);
	});

	$('#defect_amount').change(function(){
		var qty = $('#defect_qty').val();
		var price = $('#defect_price').val();
		var amount = $('#defect_amount').val();
		tkcalc(qty,price,amount);
	});

	$('#defect_tax').change(function(){
		var qty = $('#defect_qty').val();
		var price = $('#defect_price').val();
		var amount = $('#defect_amount').val();
		var tax = $('#defect_tax').val();
		tkcalc(qty,price,amount,tax);
	});

	// 불량항목 추가
	$('#defectAddConfirm').click(function(event){
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
				Swal.fire({icon: 'success', title: '성공', text: '불량항목이 추가되었습니다.', timer: 1500})
					.then(function(){
						location.href = '<?=BASE_URL?>/defect/index/' + data.idx + '?start=<?=@$_GET['start']?>&end=<?=@$_GET['end']?>&search_word=<?=@$_GET['search_word']?>&page=<?=@$_GET['page']?>';
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

	// 불량항목 수정
	$('#defectEditConfirm').click(function(event){
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
				Swal.fire({icon: 'success', title: '성공', text: '불량항목이 수정되었습니다.', timer: 1500})
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

	$('#controlDefectDeleteButton').click(function(event){
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
							Swal.fire({icon: 'success', title: '성공', text: '불량항목이 삭제되었습니다.', timer: 1500})
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

	$('#controlDefectOutputButton').click(function(event){
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
				title: '출고',
				text: '정말 출고하시겠습니까?',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: '예, 출고합니다!',
				cancelButtonText: '아니오'
			}).then(function(result){
				if (result.value){
					$('#now-loading').show();
					var request = $.ajax({url: action, type: method, data: formData, processData: false, contentType: false, dataType: "json"});
					request.done(function(data){
						$('#now-loading').hide();
						if(data.result == 'true'){
							Swal.fire({icon: 'success', title: '성공', text: '선택항목이 출고되었습니다.', timer: 1500})
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