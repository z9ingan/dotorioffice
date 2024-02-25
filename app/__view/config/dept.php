<div id="dotori-main" class="">
	<div class="container-xxl min-vh-100 px-4">
		<div class="dotori-title d-flex align-items-center">
			<div class="d-flex flex-column justify-content-center">
				<h2 class="fs-5 fw-bold text-primary mb-0"><i class="feather icon-settings me-2"></i>부서 관리</h2>
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb ms-4 mb-0 small">
						<li class="breadcrumb-item"><a href="#">설정</a></li>
						<li class="breadcrumb-item active" aria-current="page">부서 관리</li>
					</ol>
				</nav>
			</div>
		</div>
		<div class="row gx-4">
			<div class="col-lg">
				<div class="alert alert-secondary">
					<ul class="list-unstyled" id="treeTab" role="tablist">
					<?function print_dept($dept){ // 트리구조 뷰를 위한 DEPT 재귀함수 선언?>
						<li class="d-flex flex-column gap-1">
							<div class="card">
								<div class="card-body d-flex justify-content-center align-items-center gap-2 px-2 py-1">
									<button type="button" class="btn btn-link text-decoration-none text-start p-0 flex-grow-1">
										<i class="feather <?if($dept['idx']){?>icon-folder<?}else{?>icon-home<?}?> me-2"></i><strong><?=$dept['dept_name']?></strong>
									</button>
									<button type="button" class="btn btn-light px-1 py-0"><i class="feather icon-folder-plus"></i></button>
									<button type="button" class="btn btn-light px-1 py-0"><i class="feather icon-user-plus"></i></button>
								</div>
							</div>
							<ul class="user-sortable list-unstyled ps-4 d-flex flex-column gap-1">
							<?if($dept['users']){foreach($dept['users'] as $us){?>
								<li class="">
									<button class="btn btn-link bg-white text-decoration-none text-start px-2 py-1 border rounded" role="button"><i class="feather icon-user me-2"></i><?=$us['user_name']?> <small><?=$us['user_position']?></small></a>
								</li>
							<?}}?>
							</ul>
							
							<ul class="dept-sortable list-unstyled ps-4">
							<?if($dept['depts']){foreach($dept['depts'] as $dp) print_dept($dp); }?>
							</ul>
						</li>
					<?} print_dept($org); // 실행 ?>
					</ul>
				</div>
			</div>
			<div class="col-lg">
				<div class="tab-content" id="deptTabContent">
					<div class="tab-pane fade show active" id="deptHomeTab" role="tabpanel">
						<img src="<?=VIEW_URL?>/images/user_search.png" class="img-fluid">
					</div>
					<div class="tab-pane fade" id="deptAddTab" role="tabpanel">
						<form method="post" action="<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/ajax_add_dept" class="row g-3 needs-validation" novalidate>
							<input type="hidden" name="dept_idx">
							<h5 class="dept_name">부서추가</h5>
							<div class="col-lg-12">
								<label for="dept_add_dept_name" class="form-label">부서명</label>
								<input type="text" class="form-control" id="dept_add_dept_name" name="dept_name">
							</div>
							<div class="col-lg-12">
								<label for="dept_add_dept_memo" class="form-label">부서 설명</label>
								<textarea class="form-control form-control-sm" id="dept_add_dept_memo" name="dept_memo" rows="2"></textarea>
							</div>
							<div class="col-lg-12">
								<button type="submit" class="btn btn-primary w-100"><i class="feather icon-folder-plus me-2"></i>하위 부서 추가</button>
							</div>
						</form>
					</div>
					<div class="tab-pane fade" id="deptEditTab" role="tabpanel">
						<form method="post" action="<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/ajax_edit_dept" class="row g-3 needs-validation" novalidate>
							<input type="hidden" name="idx">
							<h5 class="dept_name">부서수정</h5>
							<div class="col-lg-12">
								<label for="dept_edit_dept_name" class="form-label">부서명</label>
								<input type="text" class="form-control" id="dept_edit_dept_name" name="dept_name">
							</div>
							<div class="col-lg-12">
								<label for="dept_edit_dept_memo" class="form-label">부서 설명</label>
								<textarea class="form-control form-control-sm" id="dept_edit_dept_memo" name="dept_memo" rows="2"></textarea>
							</div>
							<div class="col-lg-12">
								<button type="submit" class="btn btn-warning w-100"><i class="feather icon-folder-plus me-2"></i>부서 수정</button>
							</div>
						</form>
					</div>
					<div class="tab-pane fade" id="userAddTab" role="tabpanel">사용자 추가</div>
					<div class="tab-pane fade" id="userEditTab" role="tabpanel">사용자 수정</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
window.addEventListener('DOMContentLoaded', () => {

	// Nested demo
	var deptSortables = [].slice.call(document.querySelectorAll('.dept-sortable'));
	var userSortables = [].slice.call(document.querySelectorAll('.user-sortable'));

	// Loop through each nested sortable element
	for (var i = 0; i < deptSortables.length; i++) {
		new Sortable(deptSortables[i], {
			group: 'depts',
			animation: 150,
			fallbackOnBody: true,
			swapThreshold: 0.65
		});
	}
	for (var i = 0; i < userSortables.length; i++) {
		new Sortable(userSortables[i], {
			group: 'users',
			animation: 150,
			fallbackOnBody: true,
			swapThreshold: 0.65
		});
	}

	var buttons = document.querySelectorAll('[data-bs-toggle="pill"]');
	for (let button of buttons) {
		button.addEventListener('show.bs.tab', function (event) {
			if(button.dataset.bsTarget = '#deptAddTab'){
				event.target.querySelector('[name="dept_idx"]').value = button.dataset.deptIdx;
				event.target.querySelector('.dept_name').textContent = button.dataset.deptName;
			}else if(button.dataset.bsTarget = '#deptEditTab'){
				event.target.querySelector('[name="idx"]').value = button.dataset.idx;
				event.target.querySelector('[name="dept_name"]').value = button.dataset.deptName;
				event.target.querySelector('.dept_name').textContent = button.dataset.deptName;
				event.target.querySelector('[name="dept_memo"]').value = button.dataset.deptMemo;
			}else if(button.dataset.bsTarget = '#userAddTab'){
				event.target.querySelector('[name="dept_idx"]').value = button.dataset.deptIdx;
				event.target.querySelector('.dept_name').textContent = button.dataset.deptName;
			}
		});
	}

	// Modal들에 DEPT_IDX 변경하기
	//var modals = document.querySelectorAll('.modal');
	//for (let modal of modals) {
	//	modal.addEventListener('show.bs.modal', (e) => {
	//		var button = e.relatedTarget;
	//		modal.querySelector('[name="dept_idx"]').value = button.dataset.deptIdx;
	//	});
	//}*/
});
</script>