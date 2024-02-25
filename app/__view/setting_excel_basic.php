<div class="container-xxl py-3" style="height:calc(100vh - 3.5rem);">
	<div class="d-flex align-items-center justify-content-between mb-3">
		<h2 class="h4 font-weight-bold mb-0"><i class="feather icon-message-circle"></i> 기초재고 엑셀 추가</h2>
		<div>
			<button class="history-back btn btn-outline-dark" title="뒤로가기"><i class="feather icon-arrow-left"></i></button>
		</div>
	</div>
	<form method="post" action="<?=BASE_URL?>/setting/ajax_excel_basic" id="excelForm" class="needs-validation" novalidate>
		<div class="mb-3">
			<label for="excel_content" class="form-label">내용<span class="text-danger">*</span></label>
			<textarea class="form-control form-control-sm" name="excel_content" id="excel_content" rows="15" required></textarea>
		</div>
		<div class="text-right">
			<button type="submit" class="btn btn-primary" id="excelAddConfirm">등록</button>
		</div>
	</form>
</div>

<script>
$(function(){
	'use strict';

	// 공지사항항목 추가
	$('#excelAddConfirm').click(function(event){
		event.preventDefault();
		$('#now-loading').show();

		var form = $(this).closest('form')[0];
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
				Swal.fire({icon: 'success', title: '성공', text: '추가되었습니다.', timer: 1500})
					.then(function(){
						location.href = '<?=BASE_URL?>/setting/category';
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