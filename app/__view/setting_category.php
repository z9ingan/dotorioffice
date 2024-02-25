<div class="container-xxl py-3">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h2 class="h4 mb-0">제품코드 관리</h2>
		<div>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#categoryAddModal"><i class="feather icon-plus"></i> 추가</button>
		</div>
	</div>

	<table class="table table-sm table-bordered">
		<thead>
			<tr>
				<th class="text-nowrap" style="width:1%;">번호</th>
				<th>제품코드</th>
				<th>제품명</th>
				<th>수정</th>
				<th>삭제</th>
			</tr>
		</thead>
		<tbody>
<?
	if($categorys){ // 카테고리가 있으면
		foreach($categorys as $i => $ct){
?>
			<tr>
				<td class="text-nowrap text-center"><?=$ct['idx']?></td>
				<td><?=$ct['category_code']?></td>
				<td><?=$ct['category_name']?></td>
				<td class="text-nowrap" style="width:1%;">
					<button type="button" class="categoryEditButton btn btn-sm btn-block btn-warning" data-category-idx="<?=$ct['idx']?>" data-category-code="<?=$ct['category_code']?>" data-category-name="<?=$ct['category_name']?>">수정</button>
				</td>
				<td class="text-nowrap" style="width:1%;">
					<button type="button" class="categoryDeleteButton btn btn-sm btn-block btn-danger" data-category-idx="<?=$ct['idx']?>">삭제</button>
				</td>
			</tr>
<?
		}
	}else{ // 카테고리가 없으면
?>
			<tr>
				<td colspan="5" class="py-5 text-danger text-center">제품코드가 없습니다.</td>
			</tr>
<?
	}
?>
	</table>

</div>

<!-- 제품코드 추가 Modal -->
<div class="modal fade" id="categoryAddModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="categoryAddModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="categoryAddModalLabel">제품코드 추가</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?=BASE_URL?>/setting/ajax_add_category" id="categoryAddForm" class="needs-validation" novalidate>
					<div class="mb-3">
						<label for="add_category_code" class="form-label">코드<span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="category_code" id="add_category_code" autocomplete="off" required>
					</div>
					<div class="mb-3">
						<label for="add_category_name" class="form-label">제품명</label>
						<input type="text" class="form-control" name="category_name" id="add_category_name" autocomplete="off">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
				<button type="submit" class="btn btn-primary" form="categoryAddForm" id="categoryAddConfirm">추가</button>
			</div>
		</div>
	</div>
</div>

<!-- 제품코드 수정 Modal -->
<div class="modal fade" id="categoryEditModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="categoryEditModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="categoryEditModalLabel">제품코드 수정</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?=BASE_URL?>/setting/ajax_edit_category" id="categoryEditForm" class="needs-validation" novalidate>
					<input type="hidden" name="category_idx" id="edit_category_idx" value="">
					<div class="mb-3">
						<label for="edit_category_code" class="form-label">코드<span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="category_code" id="edit_category_code" autocomplete="off" required>
					</div>
					<div class="mb-3">
						<label for="edit_category_name" class="form-label">제품명</label>
						<input type="text" class="form-control" name="category_name" id="edit_category_name" autocomplete="off">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
				<button type="submit" class="btn btn-warning" form="categoryEditForm" id="categoryEditConfirm">수정</button>
			</div>
		</div>
	</div>
</div>

<script>
$(function(){
	'use strict';

	$('.categoryEditButton').click(function(event){
		var category_idx = $(this).data('categoryIdx');
		var category_code = $(this).data('categoryCode');
		var category_name = $(this).data('categoryName');

		$('#edit_category_idx').val(category_idx);
		$('#edit_category_code').val(category_code);
		$('#edit_category_name').val(category_name);
		$('#categoryEditModal').modal('show');
	});

	$('.categoryDeleteButton').click(function(event){
		var category_idx = $(this).data('categoryIdx');

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
				var request = $.post('<?=BASE_URL?>/setting/ajax_delete_category', {'category_idx': category_idx});
				request.done(function(data){
					if(data.result == 'true'){
						Swal.fire({icon: 'success', title: '성공', text: '제품코드가 삭제되었습니다.', timer: 1500})
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

	// 제품코드 추가
	$('#categoryAddConfirm').click(function(event){
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
				Swal.fire({icon: 'success', title: '성공', text: '제품코드가 추가되었습니다.', timer: 1500})
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

	// 제품코드 수정
	$('#categoryEditConfirm').click(function(event){
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
				Swal.fire({icon: 'success', title: '성공', text: '제품코드가 수정되었습니다.', timer: 1500})
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