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
		</div>
		<div class="row gx-4">
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
				<div class="">
					<div class="card h-100 shadow-sm">
						<div class="card-header d-xl-flex align-items-center">
							<ul class="nav nav-pills card-header-pills mx-0 mb-2 mb-xl-0" id="pills-category" role="tablist">
								<?if($edoc_categorys){
									$first_category = true;
									foreach($edoc_categorys as $edoc_category){
								?>
								<li class="nav-item" role="presentation">
									<a class="nav-link<?if($first_category){?> active<?}?>" id="pills-tab-<?=$edoc_category?>" data-bs-toggle="pill" href="#pills-<?=$edoc_category?>" role="tab" aira-controls="pills-<?=$edoc_category?>" aria-selected="<?if($first_category){?> true<?}else{?>false<?}?>"><?=$edoc_category?></a>
								</li>
								<?$first_category = false;}}?>
							</ul>
							<div class="ms-auto hstack gap-2">
								<input class="form-control" type="text" placeholder="문서양식 검색" aria-label="문서양식 검색">
								<button type="button" class="btn btn-secondary"><i class="feather icon-search"></i></button>
								<div class="vr"></div>
								<a href="<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/form" class="btn btn-outline-primary text-nowrap"><i class="feather icon-file-plus"></i> 양식 추가</a>
							</div>
							
						</div>
						<div class="card-body">
							<div class="tab-content p-3" id="pills-tabContent">
								<?if($edoc_categorys){
									$first_category = true;
									foreach($edoc_categorys as $edoc_category){
								?>
								<div class="tab-pane fade<?if($first_category){?> show active<?}?>" id="pills-<?=$edoc_category?>" role="tabpanel" aria-labelledby="pills-tab-<?=$edoc_category?>">
									<div class="row row-cols-2 row-cols-lg-3 row-cols-xl-4 g-3">
										<?foreach($edocs as $ed){
											if($ed['edoc_category'] != $edoc_category) continue;?>
										<div class="col">
											<div class="card shadow-sm h-100">
												<div class="card-body">
													<img src="<?=$ed['eform_image']?>" class="card-img" alt="<?=$ed['edoc_name']?>">
													<div class="card-img-overlay d-flex justify-content-center align-items-center" style="background-color:rgba(255,255,255,0.5);">
														<div class="dropdown position-absolute top-0 end-0">
															<button class="btn btn-outline-dark border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
																<i class="feather icon-menu"></i>
															</button>
															<ul class="dropdown-menu">
																<li><h6 class="dropdown-header">업데이트 내역</h6></li>
																<?foreach($ed['eforms'] as $ef){?>
																<li><a class="dropdown-item" href="<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/form/<?=$ef['idx']?>"><?=date('Y-m-d', $ef['time'])?> <span class="badge bg-secondary text-dark"><?=$ef['user_name']?></span></a></li>
																<?}?>
															</ul>
														</div>
														<a href="<?=BASE_URL?>/approval/doc/<?=$ed['idx']?>" class="text-reset fw-bold text-decoration-none fs-4"><?=$ed['edoc_name']?></a>
													</div>
												</div>
												<div class="card-footer small text-center">
													<?=nl2br($ed['edoc_memo'])?>
												</div>
											</div>
										</div>
										<?}?>
									</div>
								</div>
								<?$first_category = false;}}?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
