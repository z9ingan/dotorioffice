<div id="dotori-main" class="">
	<div class="container-fluid px-lg-4">
		<div class="dotori-title d-flex align-items-center">
			<div class="d-flex flex-column justify-content-center">
				<h2 class="fs-5 fw-bold text-primary mb-0"><i class="feather icon-settings me-2"></i>사용자 관리</h2>
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb ms-4 mb-0 small">
						<li class="breadcrumb-item"><a href="#">설정</a></li>
						<li class="breadcrumb-item active" aria-current="page">사용자 관리</li>
					</ol>
				</nav>
			</div>
		</div>
		<div class="dotori-body row gx-4">
			<div class="dotori-subnav pb-3">
				<div class="alert alert-secondary h-100">
					<a href="<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/user/add" class="btn btn-primary w-100 mb-3"><i class="feather icon-user-plus me-2"></i>사용자 추가</a>
					
					<div class="list-group mb-3 shadow-sm">
						<a href="<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/user?type=all" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between<?if(@$_GET['type'] == 'all'){?> active<?}?>">
							<span>전체</span>
							<span class="badge text-primary bg-soft-primary">0</span>
						</a>
						<a href="<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/user?type=active" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between<?if(@$_GET['type'] == 'active'){?> active<?}?>">
							<span><i class="feather icon-circle text-success"></i> 사용 중</span>
							<span class="badge text-success bg-soft-success">0</span>
						</a>
						<a href="<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/user?type=inactive" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between<?if(@$_GET['type'] == 'inactive'){?> active<?}?>">
							<span><i class="feather icon-slash text-danger"></i> 중지</span>
							<span class="badge text-danger bg-soft-danger">0</span>
						</a>
					</div>
				</div>
			</div>
			<div class="dotori-subbody pb-3">
				<div class="card mb-3 shadow-sm h-100">
					<div class="card-body p-0">
						<table class="table table-bordered table-outline-none table-striped table-hover table-responsive align-middle mb-0 rounded-3">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">사용자</th>
									<th scope="col">직책/직함</th>
									<th scope="col">입사일</th>
									<th scope="col">퇴사일</th>
								</tr>
							</thead>
							<?if($users){?>
							<tbody>
								<?foreach($users as $u){?>
								<tr>
									<td><?=$u['idx']?></td>
									<td>
										<div class="d-flex align-items-center">
											<div><img class="rounded-circle me-2" alt="김지성" src="<?if($u['user_photo']){?><?=$u['user_photo']?><?}else{?><?=VIEW_URL?>/images/avatar.png<?}?>" style="height:3rem;"></div>
											<div class="d-flex flex-column">
												<a href="<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/user/<?=$u['idx']?>" class="text-dark fw-bold text-decoration-none"><?=$u['user_name']?></a>
												<div class="text-muted fw-bold small"><?=$u['user_id']?></div>
											</div>
										</div>
									</td>
									<td><?=$u['user_position']?></td>
									<td><?=$u['user_entertime'] ? date('Y-m-d', $u['user_entertime']) : ''?></td>
									<td><?=$u['user_leavetime'] ? date('Y-m-d', $u['user_leavetime']) : ''?></td>
								</tr>
								<?}?>
							</tbody>
							<?}else{?>
							<tbody>
								<tr>
									<td colspan="6" class="py-5 text-center">사용자가 없습니다.</td>
								</tr>
							</tbody>
							<?}?>
						</table>
					</div>
					<div class="card-footer d-flex align-items-center justify-content-between">
						<div><?=$total?>명의 사용자가 검색 되었습니다.</div>
						<nav>
							<ul class="pagination justify-content-center mb-0">
								<? // PAGING START
								echo '<li class="page-item"><a href="?page=1" class="page-link"><i class="feather icon-chevron-left"></i></a></li>'; // 첫 페이지 
								for($i=0;$i<$page_half;$i++){
									$j = $page - $page_half + $i;
									if($j > 0){ 
										echo '<li class="page-item"><a href="?page='.$j.'" class="page-link">'.$j.'</a>'; // 현재 페이지의 앞페이지들
									}
								} 

								echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>'; // 현재 페이지 

								for($i=0;$i<$page_half;$i++){
									$j = $page + $i + 1;
									if($j <= $page_total){
										echo '<li class="page-item"><a href="?page='.$j.'" class="page-link">'.$j.'</a></li>'; // 현재 페이지의 뒷페이지들
									}
								}

								echo '<li class="page-item"><a href="?page='.$page_total.'" class="page-link"><i class="feather icon-chevron-right"></i></a></li>'; // 끝 페이지
								// PAGING END
								?>
							</ul>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>