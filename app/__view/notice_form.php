<div class="container-xxl py-3" style="height:calc(100vh - 3.5rem);">
	<div class="d-flex align-items-center justify-content-between mb-3">
		<h2 class="h4 font-weight-bold mb-0"><i class="feather icon-message-circle"></i> 공지사항</h2>
		<div>
			<button class="history-back btn btn-outline-dark" title="뒤로가기"><i class="feather icon-arrow-left"></i></button>
		</div>
	</div>
	<form method="post" id="noticeForm" class="needs-validation" novalidate>
		<div class="mb-3">
			<label for="notice_title" class="form-label">제목<span class="text-danger">*</span></label>
			<input type="text" class="form-control" name="notice_title" id="notice_title" autocomplete="off" value="<?if($notice){?><?=$notice['notice_title']?><?}?>" required>
		</div>
		<div class="mb-3">
			<label for="notice_memo" class="form-label">내용<span class="text-danger">*</span></label>
			<textarea class="form-control form-control-sm" name="notice_memo" id="notice_memo" rows="15" required><?if($notice){?><?=$notice['notice_memo']?><?}?></textarea>
		</div>
		<div class="mb-3">
			<label for="notice_file" class="d-none font-weight-bold">파일첨부</label>
			<div class="dropfile-area rounded-lg px-3 py-5">
				<span class="dropfile-btn btn btn-primary mr-3"><i class="feather icon-folder"></i> 파일 선택</span>
				<span class="dropfile-msg">또는 파일을 드래그해서 넣어주세요.</span>
				<input class="dropfile" type="file" name="notice_file[]" id="notice_file" multiple data-selected="개의 파일이 선택되었습니다.">
			</div>
		</div>
		<div class="text-right">
<?if($notice){?>
			<button type="submit" class="btn btn-warning" id="noticeAddConfirm" formaction="<?=BASE_URL?>/notice/ajax_edit_notice">수정</button>
<?}else{?>
			<button type="submit" class="btn btn-primary" id="noticeAddConfirm" formaction="<?=BASE_URL?>/notice/ajax_add_notice">등록</button>
<?}?>
		</div>
	</form>
</div>

<script>
$(function(){
	'use strict';

	// 공지사항항목 추가
	$('#noticeAddConfirm').click(function(event){
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
				Swal.fire({icon: 'success', title: '성공', text: '공지사항이 추가되었습니다.', timer: 1500})
					.then(function(){
						location.href = '<?=BASE_URL?>/notice/view/' + data.idx + '?search_word=<?=@$_GET['search_word']?>&page=<?=@$_GET['page']?>';
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

	// 공지사항항목 수정
	$('#noticeEditConfirm').click(function(event){
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
				Swal.fire({icon: 'success', title: '성공', text: '공지사항이 수정되었습니다.', timer: 1500})
					.then(function(){
						location.href = '<?=BASE_URL?>/notice/view/<?=$notice['idx']?>?search_word=<?=@$_GET['search_word']?>&page=<?=@$_GET['page']?>';
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