<div id="dotori-main" class="">
	<div class="container-xxl min-vh-100 px-lg-4 pb-3">
		<div class="dotori-title d-flex align-items-center">
			<div class="d-flex flex-column justify-content-center">
				<h2 class="fs-5 fw-bold text-primary mb-0"><i class="feather icon-settings me-2"></i>사용자 추가</h2>
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb ms-4 mb-0 small">
						<li class="breadcrumb-item"><a href="#">설정</a></li>
						<li class="breadcrumb-item">사용자 관리</li>
						<li class="breadcrumb-item active" aria-current="page">사용자 추가</li>
					</ol>
				</nav>
			</div>
		</div>
		<form method="post" action="<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/ajax_add_user" id="userAddForm" class="needs-validation" enctype="multipart/form-data" novalidate>
			<div class="row gx-4">
				<div class="col-lg-3">
					<div class="card bg-soft-secondary shadow-sm mb-3">
						<div class="card-body">
							<input type="hidden" name="user_photo" id="user_photo">
							<div class="mb-3 text-center">
								<label class="pointer" data-bs-tooltip="tooltip" title="사진 변경">
									<img class="rounded img-thumbnail" id="user_avatar" src="<?=VIEW_URL?>/images/avatar.png" alt="사진">
									<input type="file" class="visually-hidden" id="photo_input" accept="image/*">
								</label>
							</div>
							<div class="mb-3">
								<label for="user_memo" class="visually-hidden">메모</label>
								<textarea name="user_memo" rows="3" class="form-control" id="user_memo" placeholder="메모"></textarea>
							</div>
							<div class="mb-3">
								<label for="user_comcode" class="form-label">사번</label>
								<input name="user_comcode" type="text" class="form-control" id="user_comcode">
							</div>
							<div class="mb-3">
								<label for="dept_idx" class="form-label">부서</label>
								<select name="dept_idx" class="form-select" id="dept_idx">
									<option value="0"><?=$mycompany['company_name']?></option>
									<?if($depts){foreach($depts as $dp){?>
									<option value="<?=$dp['idx']?>"><?=$dp['dept_name']?></option>
									<?}}?>
								</select>
							</div>
							<div class="mb-3">
								<label for="user_position" class="form-label">직함</label>
								<input name="user_position" type="text" class="form-control" id="user_position">
							</div>
							<div class="mb-3">
								<label for="user_entertime" class="form-label">입사일</label>
								<input name="user_entertime" type="date" class="form-control" id="user_entertime">
							</div>
							<div class="mb-3">
								<label for="user_leavetime" class="form-label">퇴사일</label>
								<input name="user_leavetime" type="date" class="form-control" id="user_leavetime">
							</div>
							<div class="mb-3">
								<label for="user_level" class="form-label text-danger">권한</label>
								<select name="user_level" class="form-select" id="user_level" required>
									<option value="0">사용정지</option>
									<option value="1">일반사용자</option>
									<option value="2">관리자</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-9">
					<div class="card">
						<div class="card-body border-bottom">
							<h5 class="card-title text-primary">계정 정보</h5>
							<div class="row g-2">
								<div class="col-lg mb-3">
									<label for="user_id" class="form-label">아이디</label>
									<div class="input-group">
										<span class="input-group-text"><i class="feather icon-user"></i></span>
										<input name="user_id" type="text" class="form-control focus-first" id="user_id" required>
									</div>
								</div>
								<div class="col-lg mb-3">
									<label for="user_password" class="form-label">비밀번호</label>
									<div class="input-group">
										<span class="input-group-text"><i class="feather icon-key"></i></span>
										<input name="user_password" type="password" class="form-control" id="user_password" required>
									</div>
								</div>
								<div class="col-lg mb-3">
									<label for="password_check" class="form-label">비밀번호 확인</label>
									<div class="input-group">
										<span class="input-group-text"><i class="feather icon-check-circle"></i></span>
										<input name="password_check" type="password" class="form-control" id="password_check" required>
									</div>
								</div>
							</div>
						</div>
						<div class="card-body border-bottom">
							<h5 class="card-title text-primary">신상 정보</h5>
							<div class="row g-2">
								<div class="col-lg mb-3">
									<label for="user_name" class="form-label">이름</label>
									<input name="user_name" type="text" class="form-control" id="user_name" required>
								</div>
								<div class="col-lg mb-3">
									<label for="user_regcode" class="form-label">주민등록번호</label>
									<input name="user_regcode" type="text" class="form-control" id="user_regcode">
								</div>
								<div class="col-lg mb-3">
									<label for="user_sex" class="form-label">성별</label>
									<select name="user_sex" class="form-select" id="user_sex">
										<option value="남자">남자</option>
										<option value="여자">여자</option>
										<option value="기타">기타</option>
									</select>
								</div>
							</div>
							<div class="row g-2">
								<div class="col-lg mb-3">
									<label for="user_tel" class="form-label">자택 전화</label>
									<input name="user_tel" type="text" class="form-control" id="user_tel">
								</div>
								<div class="col-lg mb-3">
									<label for="user_mobile" class="form-label">휴대폰 번호</label>
									<input name="user_mobile" type="text" class="form-control" id="user_mobile">
								</div>
								<div class="col-lg mb-3">
									<label for="user_direct" class="form-label">내선번호</label>
									<input name="user_direct" type="text" class="form-control" id="user_direct">
								</div>
							</div>
							<div class="row g-2">
								<div class="col-lg mb-3">
									<label for="user_email" class="form-label">이메일</label>
									<div class="input-group">
										<span class="input-group-text"><i class="feather icon-mail"></i></span>
										<input name="user_email" type="email" class="form-control" id="user_email" placeholder="id@email.com">
									</div>
								</div>
							</div>
						</div>
						<div class="card-body border-bottom">
							<input type="hidden" name="user_x" id="user_x">
							<input type="hidden" name="user_y" id="user_y">
							<div class="row g-2">
								<div class="col-lg-3 mb-3">
									<label for="user_zipcode" class="form-label">자택 우편번호</label>
									<div class="input-group mb-3">
										<button class="zipcode_button btn btn-outline-primary" type="button" data-target="user"><i class="feather icon-search"></i></button>
										<input name="user_zipcode" type="text" class="form-control" id="user_zipcode">
									</div>
								</div>
								<div class="col-lg mb-3">
									<label for="user_address1" class="form-label">자택 주소</label>
									<input name="user_address1" type="text" class="form-control" id="user_address1">
								</div>
							</div>
							<div class="row g-2">
								<div class="offset-lg-3 col-lg mb-3">
									<label for="user_address2" class="form-label">자택 주소 상세</label>
									<input name="user_address2" type="text" class="form-control" id="user_address2">
								</div>
							</div>
						</div>
						<div class="card-body border-bottom">
							<h5 class="card-title text-primary">계좌 정보</h5>
							<div class="row g-2">
								<div class="col-lg-3 mb-3">
									<label for="user_bank" class="form-label">은행</label>
									<input name="user_bank" type="text" class="form-control" id="user_bank">
								</div>
								<div class="col-lg-6 mb-3">
									<label for="user_bankaccount" class="form-label">계좌번호</label>
									<input name="user_bankaccount" type="text" class="form-control" id="user_bankaccount">
								</div>
								<div class="col-lg-3 mb-3">
									<label for="user_bankholder" class="form-label">예금주</label>
									<input name="user_bankholder" type="text" class="form-control" id="user_bankholder">
								</div>
							</div>
						</div>
						<div class="card-footer border-top-0">
							<div class="d-grid">
								<button type="submit" class="btn btn-primary btn-lg fw-bold" id="userAddConfirm">사용자를 추가합니다.</button>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="photoModalLabel">이미지 조정</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="img-container mb-3">
					<img id="photo_canvas" src="<?=VIEW_URL?>/images/avatar.png">
				</div>
				<div class="text-center">
					<button type="button" class="btn btn-outline-primary" id="rotatePhoto"><i class="feather icon-rotate-cw"></i> 회전</button>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
				<button type="button" class="btn btn-primary fw-bold" id="cropPhoto">확인</button>
			</div>
		</div>
	</div>
</div>

<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=59ffe7ce1562cbbb0e8780b79bdf2ee2&libraries=services"></script>

<script>
window.addEventListener('DOMContentLoaded', () => {

	// 시그니처 패드
	//var signaturePad = new SignaturePad(document.getElementById('signature-pad'));
	//signaturePad.fromDataURL("", {'width':300, 'height':300});
	//document.getElementById('signature-clear').addEventListener('click', function () {
	//	signaturePad.clear();
	//});

	// Cropper
	var avatar = document.getElementById('user_avatar');
	var image = document.getElementById('photo_canvas');
	var input = document.getElementById('photo_input');
	var base64 = document.getElementById('user_photo');
	var modalEl = document.getElementById('photoModal');
	var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
	var cropper;

	input.addEventListener('change', function (e) {
        var files = e.target.files;
        var done = function (url) {
			input.value = '';
			image.src = url;
			modal.show();
        };
        var reader;
        var file;
        var url;

        if (files && files.length > 0) {
			file = files[0];

			if (URL) {
				done(URL.createObjectURL(file));
			} else if (FileReader) {
				reader = new FileReader();
				reader.onload = function (e) {
					done(reader.result);
				};
				reader.readAsDataURL(file);
			}
        }
	});

	modalEl.addEventListener('shown.bs.modal', function () {
		cropper = new Cropper(image, {
			aspectRatio: 1,
			viewMode: 1
		});
	});
	modalEl.addEventListener('hidden.bs.modal', function () {
		cropper.destroy();
		cropper = null;
	});

	document.getElementById('rotatePhoto').addEventListener('click', function () {
		cropper.rotate(90);
	});

	document.getElementById('cropPhoto').addEventListener('click', function () {
		var initialAvatarURL;
		var canvas;

        modal.hide();

        if (cropper) {
			canvas = cropper.getCroppedCanvas({width: 192, height: 192});
			initialAvatarURL = avatar.src;
			avatar.src = canvas.toDataURL();
			base64.value = canvas.toDataURL();
        }
	});

	// 사용자 추가 폼 전송
	document.getElementById('userAddForm').addEventListener('submit', (e) => {
        e.preventDefault()
		// 변수정리
		let form = e.currentTarget
		let action = form.getAttribute('action')
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
				Swal.fire({icon: 'success', title: '사용자 추가 성공', text: '사용자가 추가되었습니다.', timer: 1500})
				.then(function(){
					location.href = '<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/user/' + response.user_idx;
				})
			}else{
				Swal.fire({icon: 'error', title: '사용자 추가 실패', text: response.message, timer: 1500})
			}
			return
		});
	});
});
</script>