<!doctype html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>도토리오피스</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;700&display=swap">
	<link rel="stylesheet" href="<?=VIEW_URL?>/css/style.css?z=<?=time()?>">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	<style>
		html, body{overflow-x:hidden; height:100%;}
	</style>
</head>
<body class="bg-light">

<div class="h-100 py-5 d-flex align-items-center">
	<form method="post" action="<?=BASE_URL?>/authentication/ajax_login" id="login_form" class="m-auto w-100 p-3 needs-validation" novalidate style="max-width:400px;">
		<input type="hidden" name="company" value="1">
		<div class="text-center mb-4">
			<img src="<?=VIEW_URL?>/images/dotori.svg" width="48" height="48">
			<h1 class="h4">도토리 오피스</h1>
		</div>		
		<div id="alert_login" class="alert alert-danger" role="alert" style="display:none;">
			<i class="bi bi-exclamation-triangle me-2"></i>
			<span id="alert_login_text"></span>
		</div>
		<div class="form-floating mb-3">
			<input type="text" name="login_id" class="form-control" id="login_id" placeholder="ID" required>
			<label for="login_id">아이디</label>
		</div>
		<div class="form-floating mb-4">
			<input type="password" name="login_password" class="form-control" id="login_password" placeholder="Password" autocomplete="current-password" required>
			<label for="login_password">비밀번호</label>
		</div>
		<div class="d-grid">
			<button type="submit" id="loginConfirm" class="btn btn-primary btn-lg shadow-sm">로그인</button>
		</div>
	</form>
</div>

<script>
(function(){
	'use strict'

	// Focus First
	document.querySelector('#login_id').focus()

	// 로그인 처리
	document.querySelector('#login_form').addEventListener('submit', (e) => {
        e.preventDefault()
		// 변수정리
		let form = e.currentTarget
		let action = form.getAttribute('action')
		const alert_box = document.querySelector('#alert_login')
		const alert_text = document.querySelector('#alert_login_text')
		// 밸리데이션
		if (!form.checkValidity()){
			alert_text.innerText = "아이디와 비밀번호를 모두 입력해주세요."
			alert_box.style.display = "block"
			form.classList.add('was-validated')
			return;
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
				location.href = response.url
			}else{
				alert_text.innerText = response.message
				alert_box.style.display = "block"
			}
			return
		});
	});
})();
</script>

<script src="<?=VIEW_URL?>/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>