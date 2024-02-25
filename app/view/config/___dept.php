<!-- BODY -->
<main id="dotori-main" class="container-fluid px-lg-4 flex-grow-1 d-flex flex-column flex-lg-row">
	<!-- SUB NAV -->
	<div id="dotori-sub-content" class="d-flex flex-column gap-3">
		<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#childModal" data-dept-idx="0" data-dept-name="<?=$mycompany['company_name']?>"><i class="bi bi-folder-plus"></i> 신규 부서 추가</button>
		<hr class="m-0">
		<div>
			<h3 class="h6 fw-bold small">부서 목록</h3>
			<ul id="dept" class="tree">
				<?
				// 부서 목록 재귀함수
				function printDepartments($depts) {
					echo '<ul class="dept-item">';
					foreach ($depts as $dept) {
						echo '<li data-id="'.$dept['idx'].'">';
						echo '<div class="tree-item d-flex align-items-start rounded text-wrap">';
						echo '<i class="move bi bi-folder d-block text-center" style="width:24px;"></i>';
						echo '<span class="dept-item-title small pointer">'.$dept['dept_name'].'</span>';
						echo '<div class="ms-auto dropdown">';
						echo '<button type="button" class="btn btn-sm text-secondary" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots"></i></button>';
						echo '<div class="dropdown-menu dropdown-menu-end">';
						echo '<button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#childModal" data-dept-idx="'.$dept['idx'].'" data-dept-name="'.$dept['dept_name'].'"><i class="bi bi-folder-plus me-2"></i> 하위 부서 추가</button>';
						echo '<button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#renameModal" data-dept-idx="'.$dept['idx'].'" data-dept-name="'.$dept['dept_name'].'"><i class="bi bi-pencil me-2"></i> 부서명 변경</button>';
						echo '<div><hr class="dropdown-divider"></div>';
						echo '<button type="button" class="dropdown-item text-danger" data-dept-idx="'.$dept['idx'].'" disabled><i class="bi bi-trash me-2"></i> 부서 삭제</button>';
						echo '</div></div></div>';
						if (isset($dept['childs'])) {
							printDepartments($dept['childs']);
						}
						echo '</li>';
					}
					echo '</ul>';
				}
				?>
				<li>
					<div class="tree-item d-flex align-items-center rounded active">
						<i class="bi bi-building d-block text-center" style="width:24px;"></i>
						<span class="dept-item-title"><?=$mycompany['company_name']?></span>
						<div class="ms-auto dropdown">
							<button type="button" class="btn btn-sm text-secondary" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots"></i></button>
							<div class="dropdown-menu dropdown-menu-end shadow-lg">
								<button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#childModal" data-dept-idx="0" data-dept-name="<?=$mycompany['company_name']?>"><i class="bi bi-folder-plus me-2"></i> 하위 부서 추가</button>
							</div>
						</div>
					</div>
					<?printDepartments($depts);?>
				</li>
			</ul>
		</div>
		<hr class="m-0">
		<div class="text-end">
			<button type="button" class="btn btn-outline-primary btn-sm"><i class="bi bi-save"></i> 부서 순서 적용</button>
		</div>
	</div>
	
	<!-- SUB BODY -->
	<div id="dotori-sub-body" class="flex-grow-1 d-flex flex-column ms-lg-4">
		<div class="card h-100">
			<div class="card-header border-bottom-0 bg-body d-flex justify-content-between align-items-center">
				<h3 class="h5 fw-bold mb-0">부서원 목록</h3>
			</div>
			<div class="card-body">
			ㅁㄴㅇ
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="childModal" tabindex="-1" aria-labelledby="childModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="childModalLabel">하위 부서 추가</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/ajax_add_child_dept" id="childDeptAddForm" class="row gx-4 needs-validation" enctype="multipart/form-data" novalidate>
					<input type="hidden" name="dept_idx" value="">
					<div class="input-group mb-3">
						<span class="input-group-text"><i class="bi bi-folder-plus"></i></span>
						<div class="form-floating">
							<input name="dept_name" type="text" class="form-control" id="child_dept_name" placeholder="새 부서명" required>
							<label for="child_dept_name">새 부서명</label>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
				<button type="submit" form="childDeptAddForm" class="btn btn-primary fw-bold">확인</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="renameModal" tabindex="-1" aria-labelledby="renameModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="renameModalLabel">부서명 변경</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/ajax_edit_dept_name" id="deptNameEditForm" class="row gx-4 needs-validation" enctype="multipart/form-data" novalidate>
					<input type="hidden" name="dept_idx" value="">
					<div class="input-group mb-3">
						<span class="input-group-text"><i class="bi bi-pencil"></i></span>
						<div class="form-floating">
							<input name="dept_name" type="text" class="form-control" id="rename_dept_name" placeholder="부서명" required>
							<label for="rename_dept_name">부서명</label>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
				<button type="submit" form="deptNameEditForm" class="btn btn-primary fw-bold">확인</button>
			</div>
		</div>
	</div>
</div>

<script>
window.addEventListener('DOMContentLoaded', () => {

	// 부서트리 선택 이벤트
	var treeItems = document.querySelectorAll('.tree .tree-item');
	treeItems.forEach(function(link) {
		link.addEventListener('click', function(event) {
			var selectedItems = document.querySelectorAll('.tree .active');
			selectedItems.forEach(function(selectedItem) {
				selectedItem.classList.remove('active');
			});
			link.classList.add('active');
		});
	});

	// 부서트리 to JSON
	function convertNestedListToJson(ulElement) {
		const jsonArray = [];

		Array.from(ulElement.children).forEach(liElement => {
			const dataId = liElement.getAttribute('data-id');
			const nestedUlElement = liElement.querySelector('ul.dept-item');

			const jsonObject = {
				id: dataId,
				children: []
			};

			if (nestedUlElement) {
				jsonObject.children = convertNestedListToJson(nestedUlElement);
			}

			jsonArray.push(jsonObject);
		});

		return jsonArray;
	}

	// 부서트리 실행
	var nestedSortables = [].slice.call(document.querySelectorAll('#dept ul'));

	// Loop through each nested sortable element
	for (var i = 0; i < nestedSortables.length; i++) {
		new Sortable(nestedSortables[i], {
			group: 'nested',
			handle: '.move',
			animation: 150,
			fallbackOnBody: true,
			swapThreshold: 0.5,
			onEnd: () => {
				const ulElement = document.querySelector('#dept > li > ul.dept-item');
				const jsonResult = convertNestedListToJson(ulElement);
				const jsonString = JSON.stringify(jsonResult, null, 2);
				fetch('<?=BASE_URL?>/<?=DOTORI_CONTROLLER?>/ajax_update_dept_order', {
					method: 'POST',
					body: jsonString
				})
				.then((response) => response.json())
				.then((data) => {
					if (data.result == true){
						Toast.fire({icon: 'success', title: '순서 변경 성공'})
					}else{
						Toast.fire({icon: 'error', title: '순서 변경 실패'})
					}
					return;
				})
				.catch((error) => alert(error));
			}
		});
	}

	const childModal = document.getElementById('childModal');
	if (childModal) {
		childModal.addEventListener('show.bs.modal', event => {
			const button = event.relatedTarget;
			const deptIdx = button.getAttribute('data-dept-idx');
			const deptName = button.getAttribute('data-dept-name');
			const modalTitle = childModal.querySelector('.modal-title');
			const modalInputDeptIdx = childModal.querySelector('.modal-body input[name="dept_idx"]');

			modalTitle.textContent = "[" + deptName + "]에 하위 부서 추가";
			modalInputDeptIdx.value = deptIdx;
		})
	}

	const renameModal = document.getElementById('renameModal');
	if (renameModal) {
		renameModal.addEventListener('show.bs.modal', event => {
			const button = event.relatedTarget;
			const deptIdx = button.getAttribute('data-dept-idx');
			const deptName = button.getAttribute('data-dept-name');
			const modalTitle = renameModal.querySelector('.modal-title');
			const modalInputDeptIdx = renameModal.querySelector('.modal-body input[name="dept_idx"]');
			const modalInputDeptName = renameModal.querySelector('.modal-body input[name="dept_name"]');

			modalTitle.textContent = "[" + deptName + "] 부서명 변경";
			modalInputDeptIdx.value = deptIdx;
			modalInputDeptName.value = deptName;
		})
	}

	// 하위 부서 추가 폼 전송
	document.getElementById('childDeptAddForm').addEventListener('submit', (e) => {
        e.preventDefault();
		// 변수정리
		let form = e.currentTarget;
		let action = form.getAttribute('action');

		// 밸리데이션
		if (!form.checkValidity()){
			Swal.fire({icon: 'error', title: '실패', text: '빠진 내용이 없는지 확인하여 주십시오.', timer: 1500})
			form.classList.add('was-validated')
			return;
		}
		// 실행
		fetch(action, {
			method: 'POST',
			body: new FormData(form)
		})
		.then((response) => response.json())
		.then((data) => {
			if (data.result == true){
				Swal.fire({icon: 'success', title: '하위 부서 추가 성공', text: '하위 부서가 추가되었습니다.', timer: 1500})
				.then(() => {
					location.reload();
				})
			}else{
				Swal.fire({icon: 'error', title: '하위 부서 추가 실패', text: data.message, timer: 1500})
			}
			return;
		})
		.catch((error) => alert(error));
	});

	// 부서명 변경 폼 전송
	document.getElementById('deptNameEditForm').addEventListener('submit', (e) => {
        e.preventDefault();
		// 변수정리
		let form = e.currentTarget;
		let action = form.getAttribute('action');

		// 밸리데이션
		if (!form.checkValidity()){
			Swal.fire({icon: 'error', title: '실패', text: '빠진 내용이 없는지 확인하여 주십시오.', timer: 1500})
			form.classList.add('was-validated')
			return;
		}
		// 실행
		fetch(action, {
			method: 'POST',
			body: new FormData(form)
		})
		.then((response) => response.json())
		.then((data) => {
			if (data.result == true){
				Swal.fire({icon: 'success', title: '부서명 변경 성공', text: '부서명이 변경되었습니다.', timer: 1500})
				.then(() => {
					location.reload();
				})
			}else{
				Swal.fire({icon: 'error', title: '부서명 변경 실패', text: data.message, timer: 1500})
			}
			return;
		})
		.catch((error) => alert(error));
	});
});
</script>