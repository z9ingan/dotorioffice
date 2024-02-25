<!-- BODY -->
<main id="dotori-main" class="container-fluid px-lg-4 flex-grow-1 d-flex">
	<!-- SUB NAV -->
	<div id="dotori-sub-nav" class="d-flex">
		<div class="flex-grow-1 d-flex">
			<div class="offcanvas-lg offcanvas-start rounded-end flex-grow-1 bg-light" tabindex="-1" id="dotoriSubNav" aria-labelledby="dotoriSubNavLabel">
				<div class="offcanvas-header">
					<h2 class="offcanvas-title h5" id="dotoriSubNavLabel">부서 관리</h2>
					<button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#dotoriSubNav" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body">
					<div class="vstack gap-3">
						<a href="<?=BASE_URL?>/config/dept/add" class="btn btn-primary text-center"><i class="bi bi-folder-plus me-2"></i>부서 추가</a>
						<hr class="m-0">
						<ul id="dept" class="tree">
						<?
						// 부서 목록 재귀함수
						function printDepartments($depts) {
							echo '<ul class="nested">';
							foreach ($depts as $dept) {
								echo '<li class="dept-item"><i class="bi bi-folder2-open d-inline-block text-center" style="width:24px;"></i>';
								echo $dept['dept_name'];
								if (isset($dept['childs'])) {
									printDepartments($dept['childs']);
								}
								echo '</li>';
							}
							echo '</ul>';
						}
						?>
							<li><a href="#" role="button" class="active"><i class="bi bi-house d-inline-block text-center" style="width:24px;"></i><?=$mycompany['company_name']?></a>
								<?printDepartments($depts);?>
							</li>
						</ul>
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

<script>
window.addEventListener('DOMContentLoaded', () => {
	// .tree 클래스 안의 모든 a 태그를 선택합니다.
	var treeLinks = document.querySelectorAll('.tree a');

	// 각 링크에 대해 이벤트 리스너를 추가합니다.
	treeLinks.forEach(function(link) {
		// 클릭 이벤트 처리
		link.addEventListener('click', function(event) {
			event.preventDefault(); // 기본 클릭 동작 방지

			// .active 클래스를 가진 링크들을 선택합니다.
			var selectedLinks = document.querySelectorAll('.tree a.active');

			// 다른 링크에 있는 .active 클래스를 제거합니다.
			selectedLinks.forEach(function(selectedLink) {
				selectedLink.classList.remove('active');
			});

			// 클릭된 링크에 .active 클래스를 추가합니다.
			link.classList.add('active');
		});
	});

	// Nested demo
	var nestedSortables = [].slice.call(document.querySelectorAll('#dept ul'));

	// Loop through each nested sortable element
	for (var i = 0; i < nestedSortables.length; i++) {
		new Sortable(nestedSortables[i], {
			group: 'nested',
			animation: 150,
			fallbackOnBody: true,
			swapThreshold: 0.65
		});
	}
});
</script>