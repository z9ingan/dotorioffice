<div id="dotori-main" class="bg-light">
	<div class="container min-vh-100 px-lg-4">
		<div class="dotori-title d-flex align-items-center">
			<div class="d-flex flex-column justify-content-center">
				<h2 class="fs-5 fw-bold text-primary mb-0"><i class="feather icon-send me-2"></i>전자결재</h2>
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb ms-4 mb-0 small">
						<li class="breadcrumb-item"><a href="#">설정</a></li>
						<li class="breadcrumb-item">전자결재</li>
						<li class="breadcrumb-item active" aria-current="page">결재양식 추가</li>
					</ol>
				</nav>
			</div>
		</div>
		<form method="post" action="<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/ajax_add_eform" id="eformAddForm" class="needs-validation" enctype="multipart/form-data" novalidate>
			<?if(isset($eform)){?>
			<input type="hidden" name="eform_idx" value="<?=$eform['idx']?>">
			<?}?>
			<input type="hidden" name="eform_image">
			<div class="dotori-body row gx-4 pb-3">
				<div class="col-lg-3">
					<div class="card bg-soft-secondary shadow-sm mb-3">
						<div class="card-body">
							<div class="mb-3">
								<label for="edoc_category" class="form-label">문서양식 종류</label>
								<input list="edoc_categorys" name="edoc_category" id="edoc_category" class="form-control" value="<?if(isset($eform)){echo $eform['edoc_category'];}?>" required>
								<datalist id="edoc_categorys">
									<?if($edoc_categorys){foreach($edoc_categorys as $c){?>
									<option value="<?=$c?>"><?=$c?></option>
								<?}}?>
								</datalist>
							</div>
							<div class="mb-3">
								<label for="edoc_name" class="form-label">문서양식 이름</label>
								<input type="text" name="edoc_name" id="edoc_name" class="form-control" autocomplete="off" value="<?if(isset($eform)){echo $eform['edoc_name'];}?>" required>
							</div>
							<div class="mb-3">
								<label for="edoc_memo" class="form-label">문서양식 설명</label>
								<textarea name="edoc_memo" rows="5" class="form-control" id="edoc_memo" placeholder="설명"><?if(isset($eform)){echo $eform['edoc_memo'];}?></textarea>
							</div>
							<div>
								<button type="submit" class="btn btn-primary w-100"><i class="feather icon-save"></i> 저장</button>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-9">
					<textarea id="editor" name="eform_html"><?if(isset($eform)){echo $eform['eform_html'];}?></textarea>
				</div>
			</div>
		</form>
	</div>
</div>

<script src="<?=VIEW_URL?>/vendor/html2canvas/html2canvas.min.js"></script>
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

	// 사용자 추가 폼 전송
	document.getElementById('eformAddForm').addEventListener('submit', async (e) => {
        e.preventDefault();
		// 변수정리
		let form = e.currentTarget;
		let action = form.getAttribute('action');
		let thumbTarget = document.querySelector(".ck-content");
		
		// 썸네일 생성
		await html2canvas(
			thumbTarget,
			{width:600, height:300, scale:0.5, x:thumbTarget.clientWidth/2 - 300, y:5}
		).then(canvas => {
			document.querySelector('input[name="eform_image"]').value = canvas.toDataURL();
		});

		// 밸리데이션
		if (!form.checkValidity()){
			Swal.fire({icon: 'error', title: '실패', text: '빠진 내용이 없는지 확인하여 주십시오.', timer: 1500})
			form.classList.add('was-validated')
			return
		}
		
		// 실행
		fetch(action, {
			method: 'POST',
			body: new FormData(form)
		})
		.then((response) => response.json())
		.catch((error) => {
			alert(error)
		})
		.then((response) => {
			if (response.result == true){
				Swal.fire({icon: 'success', title: '문서양식 추가 성공', text: '문서양식이 추가되었습니다.'})
				.then(function(){
					location.href = '<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/form/' + response.eform_idx;
				})
			}else{
				Swal.fire({icon: 'error', title: '문서양식 추가 실패', text: response.message, timer: 1500})
			}
			return
		});
	});
});
</script>
