<!-- BODY -->
<main id="dotori-main" class="container-fluid px-lg-4 flex-grow-1 d-flex">
	<!-- SUB NAV -->
	<div id="dotori-sub-nav" class="d-flex flex-column gap-3">
		<button type="button" class="btn btn-primary" data-bs-tooltip="tooltip" data-bs-title="선택된 부서에 하위 부서를 추가합니다." data-bs-toggle="modal" data-bs-target="#childModal" data-dept-idx="" data-dept-name=""><i class="bi bi-folder-plus"></i> 부서 추가</button>
		<div class="btn-group btn-group-sm" role="group">
			<button type="button" class="btn btn-outline-primary" data-bs-tooltip="tooltip" data-bs-title="선택된 부서의 이름을 변경합니다." data-bs-toggle="modal" data-bs-target="#renameModal" data-dept-idx="" data-dept-name=""><i class="bi bi-pencil"></i></button>
			<button type="button" class="btn btn-outline-primary" data-bs-tooltip="tooltip" data-bs-title="선택된 부서를 삭제합니다." data-dept-idx="" disabled><i class="bi bi-trash"></i></button>
		</div>
		<hr class="m-0">
		<div>
			<div class="d-flex justify-content-between align-items-end mb-2">
				<h3 class="h6 fw-bold small">부서 목록</h3>
				<button type="button" class="btn btn-primary btn-sm" data-bs-tooltip="tooltip" data-bs-title="부서 순서 저장"><i class="bi bi-save"></i></button>
			</div>
			<ul id="dept" class="tree">
				<?
				// 부서 목록 재귀함수
				function printDepartments($depts) {
					echo '<ul class="dept-item">';
					foreach ($depts as $dept) {
						echo '<li data-id="'.$dept['idx'].'"><div class="tree-item d-flex align-items-center rounded">';
						echo '<i class="move bi bi-folder d-block text-center" style="width:24px;"></i>';
						echo '<span class="dept-item-title">'.$dept['dept_name'].'</span>';
						echo '<div class="ms-auto d-flex">';
						echo '<button type="button" class="btn btn-sm" data-bs-tooltip="tooltip" data-bs-title="하위 부서 추가" data-bs-toggle="modal" data-bs-target="#childModal" data-dept-idx="'.$dept['idx'].'" data-dept-name="'.$dept['dept_name'].'"><i class="bi bi-arrow-return-right"></i></button>';
						echo '<button type="button" class="btn btn-sm" data-bs-tooltip="tooltip" data-bs-title="부서명 변경" data-bs-toggle="modal" data-bs-target="#renameModal" data-dept-idx="'.$dept['idx'].'" data-dept-name="'.$dept['dept_name'].'"><i class="bi bi-pencil"></i></button>';
						echo '<button type="button" class="btn btn-sm" data-bs-tooltip="tooltip" data-bs-title="부서 삭제" data-dept-idx="'.$dept['idx'].'"><i class="bi bi-trash"></i></button>';
						echo '</div></div>';
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
						<div class="ms-auto d-flex">
							<button type="button" class="btn" data-bs-tooltip="tooltip" data-bs-title="하위 부서 추가" data-bs-toggle="modal" data-bs-target="#childModal" data-dept-idx="0" data-dept-name="<?=$mycompany['company_name']?>"><i class="bi bi-arrow-return-right"></i></button>
						</div>
					</div>
					<?printDepartments($depts);?>
				</li>
			</ul>
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