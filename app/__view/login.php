<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Manifest -->
<link rel="manifest" href="<?=BASE_DIR?>/manifest.json">

<!-- Favicon -->
<link rel="shortcut icon" href="<?=BASE_DIR?>/favicon/favicon-16x16.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?=BASE_DIR?>/favicon/favicon-180x180.png">
<link rel="icon" sizes="32x32" type="image/png" href="<?=BASE_DIR?>/favicon/favicon-32x32.png">
<link rel="icon" sizes="16x16" type="image/png" href="<?=BASE_DIR?>/favicon/favicon-16x16.png">

<!-- Web Font -->
<link href="https://hangeul.pstatic.net/hangeul_static/css/nanum-square-round.css" rel="stylesheet">

<!-- Vendor CSS -->
<link href="<?=VIEW_URL?>/vendor/bootstrap/css/bootstrap.css?z=<?=time()?>" rel="stylesheet">

<title>로그인</title>
</head>
<body class="bg-soft-dotori">

<div class="vw-100 vh-100 d-flex align-items-center justify-content-center">
	<form method="post" action="<?=BASE_URL?>/user/ajax_login" id="login_form" class="w-100 p-3 needs-validation" novalidate style="max-width:350px;">
		<input type="hidden" name="company" value="1">
		<div class="text-center mb-4">
			<img src="<?=VIEW_URL?>/images/dotori.svg" width="60" height="60">
			<h1 class="h4">도토리 오피스</h1>
		</div>
		<div class="mb-3">
			<label for="login_id" class="form-label fw-bold mb-0">아이디</label>
			<input type="text" name="login_id" class="form-control focus-first" id="login_id" required>
		</div>
		<div class="mb-4">
			<label for="login_password" class="form-label fw-bold mb-0">비밀번호</label>
			<input type="password" name="login_password" class="form-control" id="login_password" autocomplete="current-password" required>
		</div>
		<div>
			<button type="submit" id="loginConfirm" class="btn btn-dotori btn-lg w-100 shadow">로그인</button>
		</div>
	</form>
</div>

<script>
(function(){
	'use strict'

	// Focus First
	let focus_target = document.querySelector('.focus-first')
	if(focus_target != null) {
		focus_target.focus()
	}

	// 로그인 처리
	document.querySelector('#login_form').addEventListener('submit', (e) => {
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
				Swal.fire({icon: 'success', title: '로그인 성공', text: '로그인 되었습니다.', timer: 1500})
				.then(function(){
					location.href = response.url
				})
			}else{
				Swal.fire({icon: 'error', title: '로그인 실패', text: response.message, timer: 1500})
			}
			return
		});
	});
})();
</script>

<script src="<?=VIEW_URL?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?=VIEW_URL?>/vendor/sweetalert2/sweetalert2.all.min.js"></script>

</body>
</html>