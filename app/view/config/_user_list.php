<div id="dotori-body" class="d-flex flex-column">
	<div class="flex-fill d-flex flex-row">
		<div id="dotori-sub-nav" class="d-none d-lg-block p-3">
			<div class="offcanvas-lg offcanvas-end rounded-start" tabindex="-1" id="dotoriSubNav" aria-labelledby="dotoriSubNavLabel">
				<div class="offcanvas-header">
					<h5 class="offcanvas-title" id="dotoriSubNavLabel">사용자 관리</h5>
					<button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#dotoriSubNav" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body">
					<div class="w-100">
						<h2 class="d-none d-lg-block fs-5 mb-3"><i class="bi bi-gear me-2"></i>사용자 관리</h2>
						<div class="d-grid mb-3">
							<a href="<?=BASE_URL?>/config/user/add" class="btn btn-primary text-center"><i class="bi bi-person-plus me-2"></i>사용자 추가</a>
						</div>
						<div class="list-group mb-3 shadow-sm">
							<a href="<?=BASE_URL?>/<?=DOTOR_CONTROLLER?>/user?type=all" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
								<span>전체</span>
								<span class="badge text-primary bg-primary-subtle"><?=$user_total?></span>
							</a>
						</div>
						<div class="list-group mb-3 shadow-sm">
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
		<div id="dotori-sub-body" class="p-3 d-flex flex-column">
			<h4 class="fs-5">사용자 목록</h4>
			<div class="card shadow-sm h-100">
				<div class="card-body p-0">
					<table class="table table-bordered table-striped table-hover table-responsive align-middle mb-0" style="border-radius:10px;">
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
	</div>
</div>
