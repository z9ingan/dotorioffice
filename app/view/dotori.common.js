window.addEventListener('load', (e) => {

	// 로딩
	document.getElementById('now-loading').style.display = 'none';

	// 로그아웃
	document.getElementById('logout').addEventListener('click', async (e) => {
        e.preventDefault();
		let logout = e.target.getAttribute('href');
		let url = e.target.getAttribute('data-url');

		fetch(logout)
		.then((response) => response.json())
		.then((data) => {
			if (data.result == true){
				Swal.fire({
					title: '로그아웃 성공',
					text: '로그아웃 되었습니다.',
					icon: 'success',
					timer: 1500
				}).then(() => {
					location.href = url; // 메인으로 돌아가기
				});
			} else {
				Swal.fire(
					'로그아웃 실패',
					'로그아웃에 실패하였습니다.',
					'error'
				);
			}
		})
		.catch((error) => alert(error));
	});

	// 부트스트랩 툴팁 (형태 살짝 변형함)
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-tooltip="tooltip"]'));
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl);
	});

	// 다음 우편번호 & 주소 입력기
	function DaumPostcode(target) {
		new daum.Postcode({
			oncomplete: function(data) {
				// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

				// 각 주소의 노출 규칙에 따라 주소를 조합한다.
				// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
				var addr = ''; // 주소 변수
				var extraAddr = ''; // 참고항목 변수

				// 법정동명이 있을 경우 추가한다. (법정리는 제외)
				// 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
				if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
					extraAddr += data.bname;
				}
				// 건물명이 있고, 공동주택일 경우 추가한다.
				if(data.buildingName !== '' && data.apartment === 'Y'){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
				if(extraAddr !== ''){
					extraAddr = ' (' + extraAddr + ')';
				}

				document.getElementById(target + "_zipcode").value = data.zonecode;
				document.getElementById(target + "_address1").value = data.roadAddress + ' ' + extraAddr;
				//document.getElementById(target + "_x").value = result[0].x;
				//document.getElementById(target + "_y").value = result[0].y;
				document.getElementById(target + "_address2").focus();
			}
		}).open();
	}
	let zipcode_buttons = document.querySelectorAll('.zipcode_button');
	for (let button of zipcode_buttons) {
		button.addEventListener('click', (e) => {
			DaumPostcode(button.dataset.target);
		});
	}
});