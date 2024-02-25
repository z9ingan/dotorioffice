<div class="container-xxl py-3">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h2 class="h4 mb-0">사용자 관리</h2>
		<div>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#userAddModal"><i class="feather icon-user-plus"></i> 추가</button>
		</div>
	</div>

	<table class="table table-sm table-bordered text-center align-middle">
		<thead>
			<tr>
				<th class="text-nowrap" style="width:1%;">번호</th>
				<th>아이디</th>
				<th>이름</th>
				<th>부서</th>
				<th>직급/직책</th>
				<th>등록일자</th>
				<th>관리</th>
				<th>수정</th>
			</tr>
		</thead>
		<tbody>
<?foreach($users as $i => $us){?>
			<tr>
				<td class="text-nowrap text-center"><?=$us['idx']?></td>
				<td class="font-weight-bold"><?=$us['user_id']?></td>
				<td><?=$us['user_name']?></td>
				<td><?=$us['user_dept']?></td>
				<td><?=$us['user_position']?></td>
				<td><?=date('Y-m-d', $us['time'])?></td>
				<td>
<?if($us['user_level'] >= 2){?>
					<span class="text-primary font-weight-bold small">최고관리자</span>
<?}else{?>
					<div class="btn-group btn-group-sm">
						<button type="button" class="levelButton btn btn-outline-primary<?if($us['user_level'] == 1){?> active<?}?>" data-user-idx="<?=$us['idx']?>" data-value="1">일반</button>
						<button type="button" class="levelButton btn btn-outline-danger<?if($us['user_level'] == 0){?> active<?}?>" data-user-idx="<?=$us['idx']?>" data-value="0">이용정지</button>
					</div>
<?}?>
				</td>
				<td class="text-nowrap" style="width:1%;">
					<button type="button" class="userEditButton btn btn-sm btn-block btn-warning" data-user-idx="<?=$us['idx']?>" data-user-id="<?=$us['user_id']?>" data-user-name="<?=$us['user_name']?>" data-user-dept="<?=$us['user_dept']?>" data-user-position="<?=$us['user_position']?>">수정</button>
				</td>
			</tr>
<?}?>
		</tbody>
	</table>

</div>

<!-- 사용자 추가 Modal -->
<div class="modal fade" id="userAddModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="userAddModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="userAddModalLabel">사용자 추가</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?=BASE_URL?>/setting/ajax_add_user" id="userAddForm" class="needs-validation" novalidate>
					<div class="mb-3">
						<label for="add_user_id" class="form-label">아이디<span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="user_id" id="add_user_id" autocomplete="off" required>
					</div>
					<div class="mb-3">
						<label for="add_password" class="form-label">비밀번호<span class="text-danger">*</span></label>
						<input type="password" class="form-control" name="password" id="add_password" autocomplete="off" required>
					</div>
					<div class="mb-3">
						<label for="add_password_check" class="form-label">비밀번호 확인<span class="text-danger">*</span></label>
						<input type="password" class="form-control" name="password_check" id="add_password_check" autocomplete="off" required>
					</div>
					<div class="mb-3">
						<label for="add_user_name" class="form-label">이름<span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="user_name" id="add_user_name" autocomplete="off" required>
					</div>
					<div class="mb-3">
						<label for="add_user_dept" class="form-label">부서</label>
						<input type="text" class="form-control" name="user_dept" id="add_user_dept" autocomplete="off">
					</div>
					<div class="mb-3">
						<label for="add_user_position" class="form-label">직급/직책</label>
						<input type="text" class="form-control" name="user_position" id="add_user_position" autocomplete="off">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
				<button type="submit" class="btn btn-primary" form="userAddForm" id="userAddConfirm">추가</button>
			</div>
		</div>
	</div>
</div>

<!-- 사용자 수정 Modal -->
<div class="modal fade" id="userEditModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="userEditModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="userEditModalLabel">사용자 수정</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?=BASE_URL?>/setting/ajax_edit_user" id="userEditForm" class="needs-validation" novalidate>
					<input type="hidden" name="user_idx" id="edit_user_idx" value="">
					<div class="mb-3">
						<label for="edit_user_id" class="form-label">아이디<span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="user_id" id="edit_user_id" autocomplete="off" required>
					</div>
					<div class="mb-3">
						<label for="edit_password" class="form-label">비밀번호<span class="text-info">(변경 시에만 입력)</span></label>
						<input type="password" class="form-control" name="password" id="edit_password" autocomplete="off">
					</div>
					<div class="mb-3">
						<label for="edit_password_check" class="form-label">비밀번호 확인<span class="text-danger">*</span></label>
						<input type="password" class="form-control" name="password_check" id="edit_password_check" autocomplete="off">
					</div>
					<div class="mb-3">
						<label for="edit_user_name" class="form-label">이름<span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="user_name" id="edit_user_name" autocomplete="off" required>
					</div>
					<div class="mb-3">
						<label for="edit_user_dept" class="form-label">부서</label>
						<input type="text" class="form-control" name="user_dept" id="edit_user_dept" autocomplete="off">
					</div>
					<div class="mb-3">
						<label for="edit_user_position" class="form-label">직급/직책</label>
						<input type="text" class="form-control" name="user_position" id="edit_user_position" autocomplete="off">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
				<button type="submit" class="btn btn-warning" form="userEditForm" id="userEditConfirm">수정</button>
			</div>
		</div>
	</div>
</div>

<script>
$(function(){
	'use strict';

	$('.levelButton').click(function(){
		var button = $(this);
		var user_idx = $(this).data('userIdx');
		var value = $(this).data('value');

		var request = $.post("<?=BASE_URL?>/setting/ajax_edit_user_level", {'user_idx' : user_idx, 'value' : value});
		request.done(function(data){
			if(data.result == 'true'){
				const Toast = Swal.mixin({toast: true,position: 'center',showConfirmButton: false,timer: 1500});
				Toast.fire({icon: 'success', title: '권한을 변경하였습니다.'});
				if(value){
					button.siblings().removeClass('active');
					button.addClass('active');
				}else{
					button.siblings().removeClass('active');
					button.addClass('active');
				}
			}else{
				const Toast = Swal.mixin({toast: true,position: 'center',showConfirmButton: false,timer: 1500});
				Toast.fire({icon: 'error', title: '실패', text: data.message, timer: 1500});
			}
		});
		request.fail(function(){
			const Toast = Swal.mixin({toast: true,position: 'center',showConfirmButton: false,timer: 1500});
			Toast.fire({icon: 'error', title: '실패', text: '요청이 실패하였습니다. 관리자에게 문의해주세요.', timer: 1500});
		});

	});

	$('.userEditButton').click(function(event){
		var user_idx = $(this).data('userIdx');
		var user_id = $(this).data('userId');
		var user_name = $(this).data('userName');
		var user_dept = $(this).data('userDept');
		var user_position = $(this).data('userPosition');

		$('#edit_user_idx').val(user_idx);
		$('#edit_user_id').val(user_id);
		$('#edit_user_name').val(user_name);
		$('#edit_user_dept').val(user_dept);
		$('#edit_user_position').val(user_position);
		$('#userEditModal').modal('show');
	});

	$('.userDeleteButton').click(function(event){
		var user_idx = $(this).data('userIdx');

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
				var request = $.post('<?=BASE_URL?>/setting/ajax_delete_user', {'user_idx': user_idx});
				request.done(function(data){
					if(data.result == 'true'){
						Swal.fire({icon: 'success', title: '성공', text: '사용자가 삭제되었습니다.', timer: 1500})
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

	// 사용자 추가
	$('#userAddConfirm').click(function(event){
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
				Swal.fire({icon: 'success', title: '성공', text: '사용자가 추가되었습니다.', timer: 1500})
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

	// 사용자 수정
	$('#userEditConfirm').click(function(event){
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
				Swal.fire({icon: 'success', title: '성공', text: '사용자가 수정되었습니다.', timer: 1500})
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