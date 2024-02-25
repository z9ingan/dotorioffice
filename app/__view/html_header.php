<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Manifest -->
<link rel="manifest" href="<?=BASE_DIR?>/manifest.json">

<!-- Favicon -->
<link rel="shortcut icon" href="<?=VIEW_URL?>/img/favicon.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?=BASE_DIR?>/favicon/favicon-180x180.png">
<link rel="icon" sizes="32x32" type="image/png" href="<?=BASE_DIR?>/favicon/favicon-32x32.png">
<link rel="icon" sizes="16x16" type="image/png" href="<?=BASE_DIR?>/favicon/favicon-16x16.png">

<!-- Web Font -->
<link rel="stylesheet" href="https://hangeul.pstatic.net/hangeul_static/css/nanum-myeongjo.css">
<link rel="stylesheet" href="https://hangeul.pstatic.net/hangeul_static/css/nanum-gothic.css">
<link rel="stylesheet" href="https://hangeul.pstatic.net/hangeul_static/css/nanum-square.css">
<link rel="stylesheet" href="https://hangeul.pstatic.net/hangeul_static/css/nanum-square-round.css">

<!-- Dotori CSS -->
<link rel="stylesheet" href="<?=VIEW_URL?>/dotori.common.css?z=<?=time()?>">

<!-- Vendor CSS -->
<link rel="stylesheet" href="<?=VIEW_URL?>/vendor/bootstrap/css/bootstrap.css?z=<?=time()?>">
<link rel="stylesheet" href="<?=VIEW_URL?>/vendor/feather-font/css/iconfont.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css">
<link rel="stylesheet" href="<?=VIEW_URL?>/vendor/signature-pad/signature_pad.css">
<link rel="stylesheet" href="<?=VIEW_URL?>/vendor/cropperjs/cropper.css">

<title>도토리 오피스</title>
</head>
<body>

<!-- 로딩 -->
<div id="now-loading" class="position-absolute bg-white" style="left:0; top:0; right:0; bottom:0; z-index:9999;">
	<div class="w-100 h-100 d-flex align-items-center justify-content-center h4">
		<div class="spinner-border text-dotori" role="status">
			<span class="visually-hidden">Loading...</span>
		</div>
		<div class="ms-3">페이지를 불러오고 있습니다.</div>
	</div>
</div>

<nav id="dotori-header" class="navbar offcanvas-expand-lg navbar-light bg-light">
	<div id="dotori-navbar" class="container-fluid">
		<div class="d-flex justify-content-between align-items-center w-100">
			<button class="offcanvas-toggler btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
				<span class="offcanvas-toggler-icon"></span>
			</button>
			<a class="navbar-brand d-flex align-items-center" href="<?=BASE_URL?>/home">
				<img src="<?=VIEW_URL?>/images/dotori.svg" width="32" height="32">
				<span class="fs-5 ms-1">도토리 오피스</span>
			</a>
		</div>
		<div class="offcanvas offcanvas-start rounded-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
			<div class="offcanvas-header">
				<h5 class="offcanvas-title" id="offcanvasNavbarLabel">
					<img src="<?=VIEW_URL?>/images/dotori.svg" width="32" height="32">
					<span class="fs-5 ms-1">도토리 오피스</span>
				</h5>
				<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
			</div>
			<div class="offcanvas-body d-block h-100">
				<div class="d-flex flex-column h-100">
					<!--form method="get" action="#" class="d-flex align-items-center justify-content-center mb-3">
						<input type="text" name="search_word" class="form-control form-control-sm me-2" placeholder="통합검색">
						<button type="submit" class="btn btn-outline-secondary btn-sm"><i class="feather icon-search"></i></button>
					</form-->
					<ul class="nav nav-pills flex-column fw-bold">
						<li class="nav-item">
							<a class="nav-link<?if(DOTORI_CONTROLLER == "home"){?> active<?}?>" href="<?=BASE_URL?>/home"><i class="feather icon-home me-2"></i>홈</a>
						</li>
						<li class="nav-item">
							<a class="nav-link<?if(DOTORI_CONTROLLER == "mail"){?> active<?}?>" href="<?=BASE_URL?>/mail"><i class="feather icon-mail me-2"></i>메일</a>
						</li>
						<li class="nav-item">
							<a class="nav-link<?if(DOTORI_CONTROLLER == "note"){?> active<?}?>" href="<?=BASE_URL?>/note"><i class="feather icon-message-circle me-2"></i>쪽지</a>
						</li>
						<li class="nav-item">
							<a class="nav-link<?if(DOTORI_CONTROLLER == "board"){?> active<?}?>" href="<?=BASE_URL?>/board"><i class="feather icon-sidebar me-2"></i>게시판</a>
						</li>
						<li class="nav-item">
							<a class="nav-link<?if(DOTORI_CONTROLLER == "approval"){?> active<?}?>" href="<?=BASE_URL?>/approval"><i class="feather icon-send me-2"></i>전자결재</a>
						</li>
						<li class="nav-item">
							<a class="nav-link<?if(DOTORI_CONTROLLER == "schedule"){?> active<?}?>" href="<?=BASE_URL?>/schedule"><i class="feather icon-calendar me-2"></i>스케쥴</a>
						</li>
						<li class="nav-item">
							<a class="nav-link<?if(DOTORI_CONTROLLER == "project"){?> active<?}?>" href="<?=BASE_URL?>/project"><i class="feather icon-briefcase me-2"></i>프로젝트</a>
						</li>
						<li class="nav-item">
							<a class="nav-link nav-collapse<?if(DOTORI_CONTROLLER == "inven"){?> active<?}?>" data-bs-toggle="collapse" href="#inven-collapse" role="button" aria-expanded="false"><i class="feather icon-package me-2"></i>재고 관리</a>
							<ul id="inven-collapse" class="collapse nav-collapse-body nav small">
								<li class="nav-item w-100"><a class="nav-link py-1" href="<?=BASE_URL?>/inven/sell">판매 관리</a></li>
								<li class="nav-item w-100"><a class="nav-link py-1" href="<?=BASE_URL?>/inven/buy">구매 관리</a></li>
								<li class="nav-item w-100"><a class="nav-link py-1" href="<?=BASE_URL?>/inven/make">생산 관리</a></li>
								<li class="nav-item w-100"><a class="nav-link py-1" href="<?=BASE_URL?>/inven/status">재고현황</a></li>
							</ul>
						</li>
						<li class="nav-item">
							<a class="nav-link<?if(DOTORI_CONTROLLER == "webhard"){?> active<?}?>" href="<?=BASE_URL?>/webhard"><i class="feather icon-download-cloud me-2"></i>웹하드</a>
						</li>
						<li class="nav-item">
							<a class="nav-link nav-collapse<?if(DOTORI_CONTROLLER == "config"){?> focus<?}?>" data-bs-toggle="collapse" href="#config-collapse" role="button" aria-expanded="false"><i class="feather icon-settings me-2"></i>설정</a>
							<ul id="config-collapse" class="collapse nav-collapse-body nav small<?if(DOTORI_CONTROLLER == "config"){?> show<?}?>">
								<li class="nav-item w-100"><a class="nav-link py-1<?if(DOTORI_ACTION == "user"){?> active<?}?>" href="<?=BASE_URL?>/config/user">사용자 관리</a></li>
								<li class="nav-item w-100"><a class="nav-link py-1<?if(DOTORI_ACTION == "dept"){?> active<?}?>" href="<?=BASE_URL?>/config/dept">부서 관리</a></li>
								<li class="nav-item w-100"><a class="nav-link py-1" href="#">권한 관리</a></li>
							</ul>
						</li>
					</ul>
					<hr class="mt-auto mb-3">
					<div class="dropdown dropup">
						<a class="d-flex align-items-center justify-content-start w-100 text-decoration-none dropdown-toggle" href="#" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
							<img class="rounded-circle my-2" alt="김지성" src="<?=VIEW_URL?>/images/photo.png" style="height:3rem;">
							<div class="d-flex flex-column justify-content-center fw-bold ms-3 me-auto">
								<div class="small text-primary lh-1">개발팀</div>
								<div>김지성</div>
							</div>
						</a>

						<ul class="dropdown-menu shadow-sm w-100" aria-labelledby="dropdownMenuLink">
							<li><a class="dropdown-item" href="#"><i class="feather icon-user me-2"></i>내 정보</a></li>
							<li><a class="dropdown-item" href="#"><i class="feather icon-sliders me-2"></i>개인설정</a></li>
							<li><hr class="dropdown-divider"></li>
							<li><a class="dropdown-item fw-bold" href="#" id="logout" role="button"><i class="feather icon-log-out me-2"></i>로그아웃</a>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</nav>