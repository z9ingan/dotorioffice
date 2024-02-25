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

<div id="dotori-wrapper" class="d-flex flex-column">

	<!-- NAV -->
	<div id="dotori-nav" class="d-flex flex-column align-items-stretch bg-white shadow-sm">
		<div class="offcanvas-lg offcanvas-end rounded-start d-flex flex-column flex-grow-1" tabindex="-1" id="dotoriNav" aria-labelledby="dotoriNavLabel">
			<h1 id="dotori-logo" class="d-none d-lg-flex align-items-center px-3 mb-0">
				<a class="navbar-brand d-flex align-items-center" href="<?=BASE_URL?>/home">
					<img src="<?=VIEW_URL?>/images/dotori.svg" width="26" height="26">
					<span class="fs-5 ms-1">도토리 오피스</span>
				</a>
			</h1>
			<div class="offcanvas-header">
				<h1 class="offcanvas-title" id="dotoriNavLabel">
					<img src="<?=VIEW_URL?>/images/dotori.svg" width="26" height="26">
					<span class="fs-5 ms-1">도토리 오피스</span>
				</h1>
				<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" data-bs-target="#dotoriNav" aria-label="Close"></button>
			</div>
			<nav class="offcanvas-body d-flex flex-column flex-grow-1 px-3">
				<ul class="nav nav-pills flex-column flex-grow-1 small" style="--dotori-nav-pills-hover-bg: var(--bs-primary-bg-subtle);">
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
						<a class="nav-link<?if(DOTORI_CONTROLLER == "approval"){?> active<?}?>" href="<?=BASE_URL?>/approval"><i class="bi bi-clipboard-check me-2"></i>전자결재</a>
					</li>
					<li class="nav-item">
						<a class="nav-link<?if(DOTORI_CONTROLLER == "schedule"){?> active<?}?>" href="<?=BASE_URL?>/schedule"><i class="bi bi-calendar me-2"></i>스케쥴</a>
					</li>
					<li class="nav-item">
						<a class="nav-link<?if(DOTORI_CONTROLLER == "project"){?> active<?}?>" href="<?=BASE_URL?>/project"><i class="bi bi-briefcase me-2"></i>프로젝트</a>
					</li>
					<li class="nav-item">
						<a class="nav-link nav-collapse<?if(DOTORI_CONTROLLER == "inven"){?> active<?}?>" data-bs-toggle="collapse" href="#inven-collapse" role="button" aria-expanded="<?=(DOTORI_CONTROLLER == "inven" ? "true" : "false");?>"><i class="bi bi-box-seam me-2"></i>재고 관리</a>
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
						<a class="nav-link nav-collapse<?if(DOTORI_CONTROLLER == "config"){?> focus<?}?>" data-bs-toggle="collapse" href="#config-collapse" role="button" aria-expanded="<?=(DOTORI_CONTROLLER == "config" ? "true" : "false");?>"><i class="bi bi-gear me-2"></i>설정</a>
						<ul id="config-collapse" class="collapse nav-collapse-body nav<?if(DOTORI_CONTROLLER == "config"){?> show<?}?>">
							<li class="nav-item w-100"><a class="nav-link py-1<?if(DOTORI_ACTION == "user"){?> active<?}?>" href="<?=BASE_URL?>/config/user"><i class="bi bi-dot me-2"></i>사용자 관리</a></li>
							<li class="nav-item w-100"><a class="nav-link py-1<?if(DOTORI_ACTION == "dept"){?> active<?}?>" href="<?=BASE_URL?>/config/dept"><i class="bi bi-dot me-2"></i>부서 관리</a></li>
						</ul>
					</li>
				</ul>
			</nav>
		</div>
	</div>

	<!-- HEADER -->
	<header id="dotori-header" class="container-fluid px-lg-4 d-flex align-items-center">
		<button class="btn btn-lg d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#dotoriSubNav" aria-controls="dotoriSubNav" aria-label="서브메뉴"><i class="bi bi-list"></i></button>
		<div>
			<h2 class="fs-5 fw-bold mb-0"><?=$h2?></h2>
			<?if($breadcrumb){?>
			<nav aria-label="breadcrumb" class="d-none d-lg-inline-block" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);">
				<ol class="breadcrumb mb-0">
					<?foreach($breadcrumb as $bread => $href){?>
					<li class="breadcrumb-item small<?if(end($breadcrumb) == $bread){?> active<?}?>"><?if($href){?><a href="<?=$href?>" class="text-reset text-decoration-none"><?}?><?=$bread?><?if($href){?></a><?}?></li>
					<?}?>
				</ol>
			</nav>
			<?}?>
		</div>
		<div class="d-flex align-items-center ms-auto">
			<button class="btn btn-lg d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#dotoriNav" aria-controls="dotoriNav" aria-label="메인메뉴"><i class="bi bi-grid-3x3-gap-fill"></i></button>
			<div class="dropdown">
				<a class="d-block" href="#" id="dropdownMenuLink" role="button" data-bs-toggle="dropdown" data-bs-reference="parent" aria-expanded="false">
					<div class="avatar avatar-2 avatar-lg-3">
						<img class="rounded-circle" alt="김지성" src="<?=VIEW_URL?>/images/photo.png">
					</div>
				</a>
				<div class="dropdown-menu shadow-sm w-100" aria-labelledby="dropdownMenuLink" style="min-width:18rem;">
					<div class="d-flex flex-column justify-content-center align-items-center py-4">
						<div class="avatar avatar-5">
							<img class="rounded-circle" alt="김지성" src="<?=VIEW_URL?>/images/photo.png">
						</div>
						<div class="smaller">경영지원부</div>
						<div class="fw-bold"><?=$accessor['user_name']?></div>
					</div>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>내 정보</a>
					<a class="dropdown-item" href="#"><i class="bi bi-sliders me-2"></i>개인설정</a>
					<div class="dropdown-divider"></div>
					<div class="px-3">
						<a class="btn btn-sm btn-outline-secondary btn-block w-100" id="logout" role="button" href="<?=BASE_URL?>/authentication/ajax_logout" data-url="<?=BASE_URL?>/authentication/login"><i class="bi bi-box-arrow-in-right me-2"></i>로그아웃</a>
					</div>
				</div>
			</div>
		</div>
	</header>