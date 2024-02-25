<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=0.8">
	<title>도토리오피스</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;700&display=swap">
	<link rel="stylesheet" href="<?=VIEW_URL?>/css/style.css?z=<?=time()?>">
	<link rel="stylesheet" href="<?=VIEW_URL?>/css/layout.css?z=<?=time()?>">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css">
	<link rel="stylesheet" href="<?=VIEW_URL?>/vendor/signature-pad/signature_pad.css">
	<link rel="stylesheet" href="<?=VIEW_URL?>/vendor/cropperjs/cropper.css">
</head>
<body class="bg-light">

<!-- 로딩 -->
<div id="now-loading" class="position-absolute bg-white d-none" style="left:0; top:0; right:0; bottom:0; z-index:9999;">
	<div class="w-100 h-100 d-flex align-items-center justify-content-center h4">
		<div class="spinner-border" role="status">
			<span class="visually-hidden">Loading...</span>
		</div>
		<div class="ms-3">페이지를 불러오고 있습니다.</div>
	</div>
</div>

<nav id="dotori-header" class="navbar navbar-expand-lg bg-white shadow-sm">
	<div id="dotori-navbar" class="container-fluid">
		<div class="d-flex justify-content-between align-items-center w-100">
			<button class="btn btn-lg d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#dotoriNav" aria-controls="dotoriNav" aria-label="메인메뉴"><i class="bi bi-grid"></i></button>
			<h1 class="d-none d-lg-block">
				<a class="navbar-brand d-flex align-items-center" href="<?=BASE_URL?>/home">
					<img src="<?=VIEW_URL?>/images/dotori.svg" width="26" height="26">
					<span class="fs-5 ms-1">도토리 오피스</span>
				</a>
			</h1>
			<div class="d-lg-none">
				<?if(DOTORI_CONTROLLER == "home"){?>
				<a class="navbar-brand d-flex align-items-center" href="<?=BASE_URL?>/home">
					<img src="<?=VIEW_URL?>/images/dotori.svg" width="26" height="26">
					<span class="fs-5 ms-1">도토리 오피스</span>
				</a>
				<?}else if(DOTORI_CONTROLLER == "approval"){?>
				<a class="navbar-brand d-flex align-items-center" href="<?=BASE_URL?>/approval">
					<span class="fs-5 ms-1"><i class="bi bi-send me-2"></i>전자결재</span>
				</a>
				<?}else if(DOTORI_CONTROLLER == "config" && DOTORI_ACTION == "user"){?>
				<a class="navbar-brand d-flex align-items-center" href="<?=BASE_URL?>/config/user">
					<span class="fs-5 ms-1"><i class="bi bi-gear me-2"></i>사용자 관리</span>
				</a>
				<?}?>
			</div>
			<?if(DOTORI_CONTROLLER != "home"){?>
			<button class="btn btn-lg d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#dotoriSubNav" aria-controls="dotoriSubNav" aria-label="서브메뉴"><i class="bi bi-list"></i></button>
			<?}?>
		</div>
		<div class="offcanvas offcanvas-start rounded-end" tabindex="-1" id="dotoriNav" aria-labelledby="dotoriNavLabel">
			<div class="offcanvas-header">
				<h5 class="offcanvas-title" id="dotoriNavLabel">
					<img src="<?=VIEW_URL?>/images/dotori.svg" width="26" height="26">
					<span class="fs-5 ms-1">도토리 오피스</span>
				</h5>
				<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" data-bs-target="#dotoriNav" aria-label="Close"></button>
			</div>
			<div class="offcanvas-body d-block h-100">
				<div class="d-flex flex-column h-100">
					<ul class="nav nav-pills flex-column">
						<li class="nav-item">
							<a class="nav-link<?if(DOTORI_CONTROLLER == "home"){?> active<?}?>" href="<?=BASE_URL?>/home"><i class="bi bi-house me-2"></i>홈</a>
						</li>
						<li class="nav-item">
							<a class="nav-link<?if(DOTORI_CONTROLLER == "mail"){?> active<?}?>" href="<?=BASE_URL?>/mail"><i class="bi bi-envelope me-2"></i>메일</a>
						</li>
						<li class="nav-item">
							<a class="nav-link<?if(DOTORI_CONTROLLER == "note"){?> active<?}?>" href="<?=BASE_URL?>/note"><i class="bi bi-chat-right me-2"></i>쪽지</a>
						</li>
						<li class="nav-item">
							<a class="nav-link<?if(DOTORI_CONTROLLER == "board"){?> active<?}?>" href="<?=BASE_URL?>/board"><i class="bi bi-layout-text-sidebar-reverse me-2"></i>게시판</a>
						</li>
						<li class="nav-item">
							<a class="nav-link<?if(DOTORI_CONTROLLER == "approval"){?> active<?}?>" href="<?=BASE_URL?>/approval"><i class="bi bi-send me-2"></i>전자결재</a>
						</li>
						<li class="nav-item">
							<a class="nav-link<?if(DOTORI_CONTROLLER == "schedule"){?> active<?}?>" href="<?=BASE_URL?>/schedule"><i class="bi bi-calendar me-2"></i>스케쥴</a>
						</li>
						<li class="nav-item">
							<a class="nav-link<?if(DOTORI_CONTROLLER == "project"){?> active<?}?>" href="<?=BASE_URL?>/project"><i class="bi bi-briefcase me-2"></i>프로젝트</a>
						</li>
						<li class="nav-item">
							<a class="nav-link nav-collapse<?if(DOTORI_CONTROLLER == "inven"){?> active<?}?>" data-bs-toggle="collapse" href="#inven-collapse" role="button" aria-expanded="false"><i class="bi bi-box-seam me-2"></i>재고 관리</a>
							<ul id="inven-collapse" class="collapse nav-collapse-body nav">
								<li class="nav-item w-100"><a class="nav-link py-1" href="<?=BASE_URL?>/inven/sell"><i class="bi bi-dot me-2"></i>판매 관리</a></li>
								<li class="nav-item w-100"><a class="nav-link py-1" href="<?=BASE_URL?>/inven/buy"><i class="bi bi-dot me-2"></i>구매 관리</a></li>
								<li class="nav-item w-100"><a class="nav-link py-1" href="<?=BASE_URL?>/inven/make"><i class="bi bi-dot me-2"></i>생산 관리</a></li>
								<li class="nav-item w-100"><a class="nav-link py-1" href="<?=BASE_URL?>/inven/status"><i class="bi bi-dot me-2"></i>재고현황</a></li>
							</ul>
						</li>
						<li class="nav-item">
							<a class="nav-link<?if(DOTORI_CONTROLLER == "webhard"){?> active<?}?>" href="<?=BASE_URL?>/webhard"><i class="bi bi-cloud me-2"></i>웹하드</a>
						</li>
						<li class="nav-item">
							<a class="nav-link nav-collapse<?if(DOTORI_CONTROLLER == "config"){?> focus<?}?>" data-bs-toggle="collapse" href="#config-collapse" role="button" aria-expanded="false"><i class="bi bi-gear me-2"></i>설정</a>
							<ul id="config-collapse" class="collapse nav-collapse-body nav<?if(DOTORI_CONTROLLER == "config"){?> show<?}?>">
								<li class="nav-item w-100"><a class="nav-link py-1<?if(DOTORI_ACTION == "user"){?> active<?}?>" href="<?=BASE_URL?>/config/user"><i class="bi bi-dot me-2"></i>사용자 관리</a></li>
								<li class="nav-item w-100"><a class="nav-link py-1<?if(DOTORI_ACTION == "dept"){?> active<?}?>" href="<?=BASE_URL?>/config/dept"><i class="bi bi-dot me-2"></i>부서 관리</a></li>
								<li class="nav-item w-100"><a class="nav-link py-1<?if(DOTORI_ACTION == "auth"){?> active<?}?>" href="<?=BASE_URL?>/config/auth"><i class="bi bi-dot me-2"></i>권한 관리</a></li>
							</ul>
						</li>
					</ul>
					<hr class="mt-auto mb-3">
					<div class="dropdown dropup">
						<a class="d-flex align-items-center justify-content-start w-100 text-decoration-none dropdown-toggle" href="#" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
							<img class="rounded-circle my-2" alt="김지성" src="<?=VIEW_URL?>/images/photo.png" style="height:3rem;">
							<div class="d-flex flex-column justify-content-center ms-3 me-auto">
								<div class="small text-primary lh-1">개발팀</div>
								<div>김지성</div>
							</div>
						</a>
						<div class="dropdown-menu shadow-sm w-100" aria-labelledby="dropdownMenuLink">
							<a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>내 정보</a>
							<a class="dropdown-item" href="#"><i class="bi bi-sliders me-2"></i>개인설정</a>
							<div class="dropdown-divider"></div>
							<div class="p-2">
								<a class="btn btn-sm btn-outline-secondary btn-block w-100" href="#" id="logout" role="button"><i class="bi bi-box-arrow-in-right me-2"></i>로그아웃</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</nav>
