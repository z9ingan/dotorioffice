<!-- BODY -->
<main id="dotori-main" class="container-fluid px-lg-4 flex-grow-1 d-flex">
	<!-- SUB NAV -->
	<div id="dotori-sub-nav" class="d-flex">
		<div class="flex-grow-1 d-flex">
			<div class="offcanvas-lg offcanvas-start rounded-end flex-grow-1 bg-light" tabindex="-1" id="dotoriSubNav" aria-labelledby="dotoriSubNavLabel">
				<div class="offcanvas-header">
					<h2 class="offcanvas-title h5" id="dotoriSubNavLabel">사용자 관리</h2>
					<button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#dotoriSubNav" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body">
					<div class="vstack gap-3">
						<a href="<?=BASE_URL?>/config/user/add" class="btn btn-primary text-center"><i class="bi bi-person-plus me-2"></i>사용자 추가</a>
						<hr class="m-0">
						<div>
							<h3 class="h6 fw-bold small">사용자 현황</h3>
							<div class="progress" role="progressbar" aria-label="사용자 현황" aria-valuenow="<?=round($user_active/100)?>" aria-valuemin="0" aria-valuemax="100">
								<div class="progress-bar progress-bar-striped progress-bar-animated" style="width: <?=round($user_active/100)?>%"></div>
							</div>
							<div class="smaller text-secondary">
								<span class="text-primary">사용중 <?=$user_active?></span> / <?=100?>명
							</div>
						</div>
						<div>
							<h3 class="h6 fw-bold small">서버 사용량</h3>
							<div class="progress" role="progressbar" aria-label="서버 사용량" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">
								<div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 80%"></div>
							</div>
							<div class="smaller text-secondary">
								<span class="text-primary">사용량 800MB</span> / 1024MB
							</div>
						</div>
						<hr class="m-0">
						<div class="list-group">
							<a href="<?=BASE_URL?>/<?=DOTOR_CONTROLLER?>/user?type=all" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
								<span>전체</span>
								<span class="badge text-primary bg-primary-subtle"><?=$user_total?></span>
							</a>
						</div>
						<div class="list-group">
							<a href="<?=BASE_URL?>/<?=DOTOR_CONTROLLER?>/user?type=active" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
								<span>사용 중</span>
								<span class="badge text-success bg-success-subtle"><?=$user_active?></span>
							</a>
							<a href="<?=BASE_URL?>/<?=DOTOR_CONTROLLER?>/user?type=inactive" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
								<span>중지 됨</span>
								<span class="badge text-secondary bg-secondary-subtle"><?=$user_inactive?></span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- SUB BODY -->
	<div id="dotori-sub-body" class="flex-grow-1 d-flex flex-column ms-lg-4">
		<div class="card h-100">
			<div class="card-header border-bottom-0 bg-body d-flex justify-content-between align-items-center">
				<h3 class="h5 fw-bold mb-0">사용자 목록</h3>
				<form class="d-flex" role="search">
					<input class="form-control me-2" type="search" placeholder="사용자 검색" aria-label="Search">
					<button class="btn btn-outline-dark" type="submit"><i class="bi bi-search"></i></button>
				</form>
			</div>
			<div class="card-body p-0">
				<div class="px-3 py-2">
					<button type="button" class="btn btn-sm"><i class="bi bi-person-slash"></i> 선택 사용 정지</button>
				</div>
				<table class="table table-striped table-hover table-responsive align-middle small border-top mb-0">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">사용자</th>
							<th scope="col">직책/직함</th>
							<th scope="col">부서</th>
							<th scope="col"></th>
						</tr>
					</thead>
					<?if($users){?>
					<tbody>
						<?foreach($users as $u){?>
						<tr>
							<td><?=$u['idx']?></td>
							<td>
								<div class="d-flex align-items-center">
									<div><img class="rounded-circle me-2" alt="<?=$u['user_name']?>" src="<?if($u['user_photo']){?><?=$u['user_photo']?><?}else{?><?=VIEW_URL?>/images/avatar.png<?}?>" style="height:3rem;"></div>
									<div class="d-flex flex-column">
										<a href="<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/user/<?=$u['idx']?>" class="text-dark fw-bold text-decoration-none"><?=$u['user_name']?></a>
										<div class="text-muted fw-bold small"><?=$u['user_id']?></div>
									</div>
								</div>
							</td>
							<td><?=$u['user_position']?></td>
							<td><?=$u['dept_name']?></td>
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
						echo '<li class="page-item"><a href="?page=1" class="page-link"><i class="bi bi-chevron-left"></i></a></li>'; // 첫 페이지 
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

						echo '<li class="page-item"><a href="?page='.$page_total.'" class="page-link"><i class="bi bi-chevron-right"></i></a></li>'; // 끝 페이지
						// PAGING END
						?>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</main>