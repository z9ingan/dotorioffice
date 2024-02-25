<div id="dotori-main" class="bg-light">
	<div class="container-fluid min-vh-100 px-lg-4">
		<div class="dotori-title d-flex align-items-center">
			<div class="d-flex flex-column justify-content-center">
				<h2 class="fs-5 fw-bold text-primary mb-0"><i class="feather icon-send me-2"></i>전자결재</h2>
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb ms-4 mb-0 small">
						<li class="breadcrumb-item"><a href="#">설정</a></li>
						<li class="breadcrumb-item active" aria-current="page">전자결재</li>
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
			<div class="dotori-subbody py-3">
				<div class="mt-1 mb-4">
					<h4 class="fw-bold">미결문서 <span class="badge text-danger bg-soft-danger">3</span></h4>
					<p class="text-muted font-0x9">결재가 필요한 서류입니다.</p>
					<div id="incomplete-slider">
						<div>
							<div class="card shadow-sm h-100 mt-1 mb-3">
								<div class="card-body">
									<h5 class="font-weight-bold mb-4 text-truncate"><a href="http://localhost/crm/approval/view/6" class="stretched-link text-reset text-decoration-none">테스트테스트</a></h5>
									<dl class="form-row mb-0 font-0x9">
										<dt class="col-4 text-muted">기안자</dt>
										<dd class="col-8 font-weight-bold">김지성 차장</dd>
										<dt class="col-4 text-muted">기안일</dt>
										<dd class="col-8 font-weight-bold">2020-06-27</dd>
										<dt class="col-4 text-muted">문서양식</dt>
										<dd class="col-8 font-weight-bold text-truncate">지출결의서</dd>
									</dl>
								</div>
								<div class="card-footer small">도토리오피스-202006-000001</div>
							</div>
						</div>
						<div>
							<div class="card shadow-sm h-100 mt-1 mb-3">
								<div class="card-body">
									<h5 class="font-weight-bold mb-4 text-truncate"><a href="http://localhost/crm/approval/view/7" class="stretched-link text-reset text-decoration-none">aesfrgergreg</a></h5>
									<dl class="form-row mb-0 font-0x9">
										<dt class="col-4 text-muted">기안자</dt>
										<dd class="col-8 font-weight-bold">김지성 차장</dd>
										<dt class="col-4 text-muted">기안일</dt>
										<dd class="col-8 font-weight-bold">2020-06-27</dd>
										<dt class="col-4 text-muted">문서양식</dt>
										<dd class="col-8 font-weight-bold text-truncate">경영대 기금신청서</dd>
									</dl>
								</div>
								<div class="card-footer small">도토리오피스-202006-000007</div>
							</div>
						</div>
						<div>
							<div class="card shadow-sm h-100 mt-1 mb-3">
								<div class="card-body">
									<h5 class="font-weight-bold mb-4 text-truncate"><a href="http://localhost/crm/approval/view/10" class="stretched-link text-reset text-decoration-none">테스트</a></h5>
									<dl class="form-row mb-0 font-0x9">
										<dt class="col-4 text-muted">기안자</dt>
										<dd class="col-8 font-weight-bold">김지성 차장</dd>
										<dt class="col-4 text-muted">기안일</dt>
										<dd class="col-8 font-weight-bold">2020-06-28</dd>
										<dt class="col-4 text-muted">문서양식</dt>
										<dd class="col-8 font-weight-bold text-truncate">지출결의서</dd>
									</dl>
								</div>
								<div class="card-footer small">도토리오피스-202006-000010</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="mb-4">
					<h4 class="fw-bold">진행 중인 나의 결재서류 <span class="badge text-danger bg-soft-danger">4</span></h4>
					<p class="text-muted font-0x9">내가 상신하고, 아직 진행 중인 결재서류입니다.</p>
					<div id="my-ongoing-slider">
						<div>
							<div class="card shadow-sm h-100 mt-1 mb-3">
								<div class="card-body">
									<h5 class="font-weight-bold mb-4 text-truncate"><a href="http://localhost/crm/approval/view/6" class="stretched-link text-reset text-decoration-none">테스트테스트</a></h5>
									<dl class="form-row mb-0 font-0x9">
										<dt class="col-4 text-muted">기안일</dt>
										<dd class="col-8 font-weight-bold">2020-06-27</dd>
										<dt class="col-4 text-muted">문서양식</dt>
										<dd class="col-8 font-weight-bold">지출결의서</dd>
										<dt class="col-4 text-danger">결재대기</dt>
										<dd class="col-8 font-weight-bold text-truncate">김지성 차장</dd>
									</dl>
								</div>
								<div class="card-footer small">도토리오피스-202006-000001</div>
							</div>
						</div>
						<div>
							<div class="card shadow-sm h-100 mt-1 mb-3">
								<div class="card-body">
									<h5 class="font-weight-bold mb-4 text-truncate"><a href="http://localhost/crm/approval/view/7" class="stretched-link text-reset text-decoration-none">aesfrgergreg</a></h5>
									<dl class="form-row mb-0 font-0x9">
										<dt class="col-4 text-muted">기안일</dt>
										<dd class="col-8 font-weight-bold">2020-06-27</dd>
										<dt class="col-4 text-muted">문서양식</dt>
										<dd class="col-8 font-weight-bold">경영대 기금신청서</dd>
										<dt class="col-4 text-danger">결재대기</dt>
										<dd class="col-8 font-weight-bold text-truncate">김지성 차장</dd>
									</dl>
								</div>
								<div class="card-footer small">도토리오피스-202006-000007</div>
							</div>
						</div>
						<div>
							<div class="card shadow-sm h-100 mt-1 mb-3">
								<div class="card-body">
									<h5 class="font-weight-bold mb-4 text-truncate"><a href="http://localhost/crm/approval/view/10" class="stretched-link text-reset text-decoration-none">테스트</a></h5>
									<dl class="form-row mb-0 font-0x9">
										<dt class="col-4 text-muted">기안일</dt>
										<dd class="col-8 font-weight-bold">2020-06-28</dd>
										<dt class="col-4 text-muted">문서양식</dt>
										<dd class="col-8 font-weight-bold">지출결의서</dd>
										<dt class="col-4 text-danger">결재대기</dt>
										<dd class="col-8 font-weight-bold text-truncate">김지성 차장</dd>
									</dl>
								</div>
								<div class="card-footer small">도토리오피스-202006-000010</div>
							</div>
						</div>
						<div>
							<div class="card shadow-sm h-100 mt-1 mb-3">
								<div class="card-body">
									<h5 class="font-weight-bold mb-4 text-truncate"><a href="http://localhost/crm/approval/view/11" class="stretched-link text-reset text-decoration-none">6월 지출내역ㄷㄱ솧ㅅ</a></h5>
									<dl class="form-row mb-0 font-0x9">
										<dt class="col-4 text-muted">기안일</dt>
										<dd class="col-8 font-weight-bold">2020-06-28</dd>
										<dt class="col-4 text-muted">문서양식</dt>
										<dd class="col-8 font-weight-bold">지출결의서</dd>
										<dt class="col-4 text-danger">결재대기</dt>
										<dd class="col-8 font-weight-bold text-truncate">김양수 팀장</dd>
									</dl>
								</div>
								<div class="card-footer small">도토리오피스-202006-000011</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<script>
window.addEventListener('DOMContentLoaded', () => {
	var incomplete_slider = tns({
		container: '#incomplete-slider',
		navPosition: 'bottom',
		controls: false,
		loop: false,
		mouseDrag: true,
		items: 1,
		gutter: 20,
		responsive: {
			576: {items: 2},
			768: {items: 3},
			992: {items: 2},
			1200:{items: 3},
			1600:{items: 4},
			2000:{items: 5}
		}
	});
	var my_ongoing_slider = tns({
		container: '#my-ongoing-slider',
		navPosition: 'bottom',
		controls: false,
		loop: false,
		mouseDrag: true,
		items: 1,
		gutter: 20,
		responsive: {
			576: {items: 2},
			768: {items: 3},
			992: {items: 2},
			1200:{items: 3},
			1600:{items: 4},
			2000:{items: 5}
		}
	});
});
</script>