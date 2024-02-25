<div id="dotori-main" class="bg-light">
	<div class="container-fluid min-vh-100 px-lg-4">
		<div class="dotori-title d-flex align-items-center">
			<div class="d-flex flex-column justify-content-center">
				<h2 class="fs-5 fw-bold text-primary mb-0"><i class="feather icon-send me-2"></i>전자결재</h2>
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb ms-4 mb-0 small">
						<li class="breadcrumb-item"><a href="#">설정</a></li>
						<li class="breadcrumb-item">전자결재</li>
						<li class="breadcrumb-item active" aria-current="page">문서양식 선택</li>
					</ol>
				</nav>
			</div>
			<div class="ms-auto">
				<a href="<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/doc" class="btn btn-outline-danger"><i class="feather icon-x"></i> 취소</a>
				<button type="button" class="approvalTempButton btn btn-info" form="approvalAddForm" formaction="<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/ajax_add_approval">임시저장</button>
				<button type="button" class="approvalAddConfirm btn btn-primary" form="approvalAddForm" formaction="<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/ajax_add_approval"><i class="feather icon-send"></i> 상신</button>
			</div>
		</div>
		<div class="dotori-body row gx-4 pb-3">
			<div class="dotori-subnav">
				<div class="btn-group w-100 mb-3" role="group">
					<a href="<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/doc" class="btn btn-primary w-100 text-start">전자결재 작성</a>
					<button type="button" class="btn btn-primary"><i class="feather icon-settings"></i></button>
				</div>
				<div class="list-group mb-3 shadow-sm">
					<a href="http://localhost/crm/approval/archive/temp" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
						<span>임시보관함</span>
						<span class="badge text-primary bg-soft-primary">0</span>
					</a>
				</div>
				<div class="list-group mb-3 shadow-sm">
					<a href="http://localhost/crm/approval/archive/report" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
						<span>상신함</span>
						<span class="badge text-primary bg-soft-primary">0</span>
					</a>
					<a href="http://localhost/crm/approval/archive/sign" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
						<span>결재함</span>
						<span class="badge text-primary bg-soft-primary">0</span>
					</a>
					<a href="http://localhost/crm/approval/archive/refer" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
						<span>수신함</span>
						<span class="badge text-primary bg-soft-primary">0</span>
					</a>
				</div>
				<div class="list-group mb-3 shadow-sm">
					<a href="http://localhost/crm/approval/archive/incomplete" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
						<span>미결문서</span>
						<span class="badge text-primary bg-soft-primary">0</span>
					</a>
					<a href="http://localhost/crm/approval/archive/ongoing" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
						<span>진행문서</span>
						<span class="badge text-primary bg-soft-primary">0</span>
					</a>
					<a href="http://localhost/crm/approval/archive/complete" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
						<span>완료문서</span>
						<span class="badge text-primary bg-soft-primary">0</span>
					</a>
					<a href="http://localhost/crm/approval/archive/reject" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
						<span>반려문서</span>
						<span class="badge text-primary bg-soft-primary">0</span>
					</a>
				</div>
			</div>
			<div class="dotori-subbody">
				<form method="post" id="approvalAddForm" class="needs-validation h-100" enctype="multipart/form-data" novalidate>
					<input type="hidden" name="approval_temp" value="">
					<input type="hidden" name="edoc_idx" value="<?=$edoc['idx']?>">
					<div class="card h-100 shadow">
						<div class="card-body">
							<div class="mx-auto" style="max-width:210mm;">
								<h6 class="h1 fw-bold text-center py-5"><?=$edoc['edoc_name']?></h6>
								<div class="row">
									<legend class="col-sm-2 col-form-label fw-bold"><span>결재라인</span> <button type="button" class="btn btn-sm btn-primary py-0 px-1" data-bs-toggle="modal" data-bs-target="#elineEditModal"><i class="feather icon-plus"></i></button></legend>
									<div id="eline_box" class="col-sm-10 d-flex flex-wrap align-items-center">
									</div>
								</div>
								<div class="row">
									<legend class="col-sm-2 col-form-label fw-bold"><span>참조</span> <button type="button" class="btn btn-sm btn-primary py-0 px-1" data-bs-toggle="modal" data-bs-target="#referEditModal"><i class="feather icon-plus"></i></button></legend>
									<div id="refer_box" class="col-sm-10 d-flex flex-wrap align-items-center">
									</div>
								</div>
								<div class="row">
									<label for="" class="col-sm-2 col-form-label fw-bold">제목</label>
									<div class="col-sm-10">
										<input type="text" name="approval_title" class="form-control" autocomplete="off">
									</div>
								</div>
								<div class="row">
									<label for="" class="col-sm-2 col-form-label fw-bold">파일첨부</label>
									<div class="col-sm-10">
										<div class="dropfile-area rounded-10 p-3 py-4">
											<span class="dropfile-btn btn btn-primary mr-3"><i class="feather icon-folder"></i> 파일 선택</span>
											<span class="dropfile-msg">또는 파일을 드래그해서 넣어주세요.</span>
											<input class="dropfile" type="file" name="approval_file[]" id="approval_file" multiple data-selected="개의 파일이 선택되었습니다.">
										</div>
									</div>
								</div>
								<textarea name="approval_memo" id="editor"><?=$edoc['eform_html']?></textarea>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- 결재라인 수정 Modal -->
<div class="modal fade" id="elineEditModal" tabindex="-1" role="dialog" aria-labelledby="elineEditModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="elineEditModalLabel">결재라인 수정</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-5">
						<div class="card overflow-scroll" style="height:200px;">
							<div class="list-group list-group-flush" id="selectElines">
								<?if($users){foreach($users as $us){?>
								<button type="button" class="list-group-item list-group-item-action py-1" data-value="<?=$us['idx']?>"><?=$us['user_name']?> <?=$us['user_position']?></button>
								<?}}?>
							</div>
						</div>
					</div>
					<div class="col-2">
						<div class="vstack gap-2">
							<button type="button" class="btn btn-outline-secondary"><i class="feather icon-arrow-right"></i></button>
							<button type="button" class="btn btn-outline-secondary"><i class="feather icon-arrow-left"></i></button>
						</div>
					</div>
					<div class="col-5">
						<div class="card overflow-scroll" style="height:200px;">
							<div class="list-group list-group-flush" id="selectedElines">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
			</div>
		</div>
	</div>
</div>

<!-- 참조 수정 Modal -->
<div class="modal fade" id="referEditModal" tabindex="-1" role="dialog" aria-labelledby="referEditModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="referEditModalLabel">결재라인 수정</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<select id="selectRefers" multiple="multiple">
<?if($users){foreach($users as $us){?>
					<option value="<?=$us['idx']?>" data-name="<?=$us['user_name']?>"><?=$us['user_name']?> <?=$us['user_position']?></option>
<?}}?>
				</select>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
			</div>
		</div>
	</div>
</div>

<script src="<?=VIEW_URL?>/vendor/ckeditor5/ckeditor.js?z=<?=time()?>"></script>
<script src="<?=VIEW_URL?>/vendor/ckeditor5/translations/ko.js"></script>
<script>
window.addEventListener('DOMContentLoaded', () => {
	ClassicEditor
		.create( document.querySelector( '#editor' ), {
			language: 'ko',
			toolbar: ['heading', '|', 'fontFamily', 'fontSize', 'fontColor',  'FontBackgroundColor', '|', 'bold', 'italic', 'underline', 'strikethrough', '|', 'alignment', 'indent', 'outdent', 'bulletedList', 'numberedList', 'todoList', 'horizontalLine', '|', 'imageUpload', 'insertTable', '|', 'sourceEditing'],
			fontFamily: {
				options: [
					"default",
					"나눔고딕, NanumGothic",
					"나눔명조, NanumMyeongjo",
					"나눔스퀘어, NanumSquare",
					"나눔스퀘어라운드, NanumSqureRound",
				],
			},
		})
		.then( editor => {
			window.editor = editor;
		})
		.catch( error => {
			console.error( error );
		});

	let selectElines = document.querySelectorAll('#selectElines > button');
	for (const button of selectElines) {
		button.addEventListener('click', (e) => {
			document.getElementById('selectedElines').append(e.currentTarget);
			//e.currentTarget.remove();
		})
	}
});
</script>
<script>
/*
$(function() {
	'use strict';

	function updateElines(){
		<?if($preelines){foreach($preelines as $i => $pl){?>
			$('#selectElines').multiSelect('select', '<?=$pl['preeline_user_idx']?>');
		<?}}?>
	}

	function updateRefers(){
		<?if($prerefers){foreach($prerefers as $i => $pr){?>
			$('#selectRefers').multiSelect('select', '<?=$pr['prerefer_user_idx']?>');
		<?}}?>
	}

	$('#selectElines').multiSelect({
		keepOrder: true,
		selectableHeader: "<div class='h6 font-weight-bold'>사용자 목록</div>",
		selectionHeader: "<div class='h6 font-weight-bold'>선택된 결재자</div>",
		afterInit: function(ms){
			updateElines();
		},
		afterSelect: function(value){
			$('#eline_box').append('<span id="eline_user_idx_' + value + '" class="badge badge-soft-primary font-1x mr-1 my-1"><input type="hidden" name="eline_user_idx[]" value="' + value + '"> ' + $('#selectElines').find('option[value="' + value + '"]').data('name') + '</span>');
		},
		afterDeselect: function(value){
			$('#eline_user_idx_' + value).remove();
		}
	});

	$('#selectRefers').multiSelect({
		keepOrder: true,
		selectableHeader: "<div class='h6 font-weight-bold'>사용자 목록</div>",
		selectionHeader: "<div class='h6 font-weight-bold'>선택된 참조자</div>",
		afterInit: function(ms){
			updateRefers();
		},
		afterSelect: function(value){
			$('#refer_box').append('<span id="refer_user_idx_' + value + '" class="badge badge-soft-primary font-1x mr-1 my-1"><input type="hidden" name="refer_user_idx[]" value="' + value + '"> ' + $('#selectRefers').find('option[value="' + value + '"]').data('name') + '</span>');
		},
		afterDeselect: function(value){
			$('#refer_user_idx_' + value).remove();
		}
	});

	$('#approval_memo').summernote({
		placeholder: '전자결재 본문',
		lang: 'ko-KR',
		airMode: true
	});

	$('.approvalTempButton').click(function(event){
		event.preventDefault();
		$('#now-loading').show();
		var form_id = $(this).attr('form');
		var form = $('#'+form_id)[0];
		var url = $(this).attr("formaction");
		$('[name="approval_temp"]').val('temp');
		var form_data = new FormData(form);

		if(form.checkValidity() === false){
			Swal.fire({icon: 'error', title: '실패', text: '빠진 내용이 없는지 확인하여 주십시오.', timer: 1500});
			form.classList.add('was-validated');
			$('#now-loading').hide();
			return false;
		}

		var request = $.ajax({url: url, method: 'POST', data: form_data, processData: false, contentType: false, dataType: "json"});
		request.done(function(data){
			$('#now-loading').hide();
			if(data.result == 'true'){
				Swal.fire({icon: 'success', title: '성공', text: '결재서류가 임시저장 되었습니다.', timer: 1500})
					.then(function(){
						location.href = '<?=BASE_URL?>/approval/view/' + data.idx;
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

	$('.approvalAddConfirm').click(function(event){
		event.preventDefault();
		$('#now-loading').show();
		var form_id = $(this).attr('form');
		var form = $('#'+form_id)[0];
		var url = $(this).attr("formaction");
		var form_data = new FormData(form);

		if(form.checkValidity() === false){
			Swal.fire({icon: 'error', title: '실패', text: '빠진 내용이 없는지 확인하여 주십시오.', timer: 1500});
			form.classList.add('was-validated');
			$('#now-loading').hide();
			return false;
		}

		var request = $.ajax({url: url, method: 'POST', data: form_data, processData: false, contentType: false, dataType: "json"});
		request.done(function(data){
			$('#now-loading').hide();
			if(data.result == 'true'){
				Swal.fire({icon: 'success', title: '성공', text: '결재서류가 상신되었습니다.', timer: 1500})
					.then(function(){
						location.href = '<?=BASE_URL?>/approval/view/' + data.idx;
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
*/
</script>
