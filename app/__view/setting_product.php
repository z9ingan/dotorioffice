<div class="container-xxl py-3">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h2 class="h4 mb-0">품목코드 관리</h2>
		<div>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#productAddModal"><i class="feather icon-plus"></i> 추가</button>
		</div>
	</div>

	<div id="search">
		<form method="get" class="card card-body shadow-sm bg-light pb-0 mb-3">
			<div class="row mb-3">
				<label for="search_word" class="col-md-2 col-form-label">검색어</label>
				<div class="col-md-6">
					<input type="text" name="search_word" id="search_word" class="form-control" value="<?=$search_word?>">
				</div>
				<div class="col-md"><button type="submit" class="btn btn-block btn-primary">검색</button></div>
			</div>
		</form>
	</div>

	<table class="table table-sm table-bordered small text-center align-middle">
		<thead>
			<tr>
				<th class="text-nowrap" style="width:1%;">번호</th>
				<th>제품코드</th>
				<th>제품명</th>
				<th>품목코드</th>
				<th>컬러</th>
				<th>사이즈</th>
				<th>기초재고</th>
				<th>수정</th>
				<th>삭제</th>
			</tr>
		</thead>
		<tbody>
<?
	if($products){ // 품목이 있으면
		foreach($products as $i => $pd){
?>
			<tr>
				<td class="text-nowrap"><?=$pd['idx']?></td>
				<td><?=$pd['category_code']?></td>
				<td><?=$pd['category_name']?></td>
				<td class="font-weight-bold"><?=$pd['product_code']?></td>
				<td><?=$pd['product_color']?></td>
				<td><?=$pd['product_size']?></td>
				<td contenteditable="true" class="basicQty user-select-all" style="background-color:rgba(255,228,0,0.2);" data-product-idx="<?=$pd['idx']?>" data-basic-qty="<?=$pd['basic_qty']?>"><?=$pd['basic_qty']?></td>
				<td class="text-nowrap" style="width:1%;">
					<button type="button" class="productEditButton btn btn-sm btn-block btn-warning" data-product-idx="<?=$pd['idx']?>" data-category-idx="<?=$pd['category_idx']?>" data-product-code="<?=$pd['product_code']?>"  data-product-color="<?=$pd['product_color']?>" data-product-size="<?=$pd['product_size']?>">수정</button>
				</td>
				<td class="text-nowrap" style="width:1%;">
					<button type="button" class="productDeleteButton btn btn-sm btn-block btn-danger" data-product-idx="<?=$pd['idx']?>">삭제</button>
				</td>
			</tr>
<?
		}
	}else{ // 품목이 없으면
?>
			<tr>
				<td colspan="8" class="py-5 text-danger text-center">품목코드가 없습니다.</td>
			</tr>
<?
	}
?>
	</table>

	<nav class="">
		<ul class="pagination pagination-sm justify-content-center">
<? // PAGING START
echo '<li class="page-item"><a href="?search_word='.@$_GET['search_word'].'&page=1" class="page-link"><i class="feather icon-chevron-left"></i></a></li>'; // 첫 페이지 
for($i=0;$i<$page_half;$i++){
	$j = $page - $page_half + $i;
	if($j > 0){ 
		echo '<li class="page-item"><a href="?search_word='.@$_GET['search_word'].'&page='.$j.'" class="page-link">'.$j.'</a>'; // 현재 페이지의 앞페이지들
	}
} 

echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>'; // 현재 페이지 

for($i=0;$i<$page_half;$i++){
	$j = $page + $i + 1;
	if($j <= $page_total){
		echo '<li class="page-item"><a href="?search_word='.@$_GET['search_word'].'&page='.$j.'" class="page-link">'.$j.'</a></li>'; // 현재 페이지의 뒷페이지들
	}
}

echo '<li class="page-item"><a href="?search_word='.@$_GET['search_word'].'&page='.$page_total.'" class="page-link"><i class="feather icon-chevron-right"></i></a></li>'; // 끝 페이지
// PAGING END
?>
		</ul>
	</nav>

</div>

<!-- 품목코드 추가 Modal -->
<div class="modal fade" id="productAddModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="productAddModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="productAddModalLabel">품목코드 추가</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?=BASE_URL?>/setting/ajax_add_product" id="productAddForm" class="needs-validation" novalidate>
					<div class="mb-3">
						<label for="add_category_idx" class="form-label">제품코드<span class="text-danger">*</span></label>
						<select class="form-select" name="category_idx" id="add_category_idx" required>
<?if($categorys){foreach($categorys as $ct){?>
							<option value="<?=$ct['idx']?>"><?=$ct['category_code']?> / <?=$ct['category_name']?></option>
<?}}?>
						</select>
					</div>
					<div class="mb-3">
						<label for="add_product_code" class="form-label">품목코드<span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="product_code" id="add_product_code" autocomplete="off" required>
					</div>
					<div class="mb-3">
						<label for="add_product_color" class="form-label">컬러<span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="product_color" id="add_product_color" autocomplete="off" required>
					</div>
					<div class="mb-3">
						<label for="add_product_size" class="form-label">사이즈<span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="product_size" id="add_product_size" autocomplete="off" required>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
				<button type="submit" class="btn btn-primary" form="productAddForm" id="productAddConfirm">추가</button>
			</div>
		</div>
	</div>
</div>

<!-- 제품코드 수정 Modal -->
<div class="modal fade" id="productEditModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="productEditModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="productEditModalLabel">제품코드 수정</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?=BASE_URL?>/setting/ajax_edit_product" id="productEditForm" class="needs-validation" novalidate>
					<input type="hidden" name="product_idx" id="edit_product_idx" value="">
					<div class="mb-3">
						<label for="edit_category_idx" class="form-label">제품코드<span class="text-danger">*</span></label>
						<select class="form-select" name="category_idx" id="edit_category_idx" required>
<?if($categorys){foreach($categorys as $ct){?>
							<option value="<?=$ct['idx']?>"><?=$ct['category_code']?> / <?=$ct['category_name']?></option>
<?}}?>
						</select>
					</div>
					<div class="mb-3">
						<label for="edit_product_code" class="form-label">품목코드<span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="product_code" id="edit_product_code" autocomplete="off" required>
					</div>
					<div class="mb-3">
						<label for="edit_product_color" class="form-label">컬러<span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="product_color" id="edit_product_color" autocomplete="off" required>
					</div>
					<div class="mb-3">
						<label for="edit_product_size" class="form-label">사이즈<span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="product_size" id="edit_product_size" autocomplete="off" required>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
				<button type="submit" class="btn btn-warning" form="productEditForm" id="productEditConfirm">수정</button>
			</div>
		</div>
	</div>
</div>

<script>
$(function(){
	'use strict';

	$('.productEditButton').click(function(event){
		var product_idx = $(this).data('productIdx');
		var category_idx = $(this).data('categoryIdx');
		var product_code = $(this).data('productCode');
		var product_color = $(this).data('productColor');
		var product_size = $(this).data('productSize');

		$('#edit_product_idx').val(product_idx);
		$('#edit_category_idx option').removeAttr('selected');
		$('#edit_category_idx option[value="' + category_idx + '"]').attr('selected', 'selected');
		$('#edit_product_code').val(product_code);
		$('#edit_product_color').val(product_color);
		$('#edit_product_size').val(product_size);
		$('#productEditModal').modal('show');
	});

	$('.basicQty').blur(function(event){
		var tablecell = $(this);
		var product_idx = $(this).data('productIdx');
		var original_qty = $(this).data('basicQty');
		var value = $.trim($(this).text());
		if(original_qty != value){
			Swal.fire({
				icon: 'warning',
				title: '기초재고 변경',
				text: '정말 변경하시겠습니까?',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: '예, 변경합니다!',
				cancelButtonText: '아니오'
			}).then(function(result){
				if (result.value){
					var request = $.post('<?=BASE_URL?>/setting/ajax_set_basic_qty', {'product_idx': product_idx, 'basic_qty': value});
					request.done(function(data){
						if(data.result == 'true'){
							const Toast = Swal.mixin({toast: true, position: 'center', showConfirmButton: false, timer: 1500});
							Toast.fire({icon: 'success', title: '기초재고가 변경되었습니다.'});
							tablecell.data('basicQty', value);
						}else{
							const Toast = Swal.mixin({toast: true, position: 'center', showConfirmButton: false, timer: 1500});
							Toast.fire({icon: 'error', title: '실패', text: data.message, timer: 1500});
							tablecell.text(original_qty);
						}
					});
					request.fail(function(){
						Swal.fire({icon: 'error', title: '실패', text: '요청이 실패하였습니다. 관리자에게 문의해주세요.', timer: 1500});
						tablecell.text(original_qty);
					});
				}
			});
		}else{
			tablecell.text(original_qty);
		}
	});

	$('.productDeleteButton').click(function(event){
		var product_idx = $(this).data('productIdx');

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
				var request = $.post('<?=BASE_URL?>/setting/ajax_delete_product', {'product_idx': product_idx});
				request.done(function(data){
					if(data.result == 'true'){
						Swal.fire({icon: 'success', title: '성공', text: '품목코드가 삭제되었습니다.', timer: 1500})
							.then(function(){
								location.reload();
							});
					}else{
						Swal.fire({icon: 'error', title: '실패', text: data.message, timer: 1500});
					}
				});
				request.fail(function(){
					Swal.fire({icon: 'error', title: '실패', text: '요청이 실패하였습니다. 관리자에게 문의해주세요.', timer: 1500});
				});
			}
		});
	});

	// 품목코드 추가
	$('#productAddConfirm').click(function(event){
		event.preventDefault();
		$('#now-loading').show();

		var form_id = $(this).attr('form');
		var form = $('#'+form_id)[0];
		var action = $(form).attr("action");
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
				Swal.fire({icon: 'success', title: '성공', text: '품목코드가 추가되었습니다.', timer: 1500})
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

	// 품목코드 수정
	$('#productEditConfirm').click(function(event){
		event.preventDefault();
		$('#now-loading').show();

		var form_id = $(this).attr('form');
		var form = $('#'+form_id)[0];
		var action = $(form).attr("action");
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
				Swal.fire({icon: 'success', title: '성공', text: '품목코드가 수정되었습니다.', timer: 1500})
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

});
</script>