<div class="container-xxl py-3" style="height:calc(100vh - 3.5rem);">
	<div class="d-flex align-items-center justify-content-between mb-3">
		<h2 class="h4 font-weight-bold mb-0"><i class="feather icon-message-circle"></i> 공지사항</h2>
		<div>
			<a href="<?=BASE_URL?>/notice" class="btn btn-outline-dark" title="검색 취소"><i class="feather icon-rotate-ccw"></i></a>
			<button type="button" class="btn btn-outline-dark" data-toggle="collapse" data-target="#search" aria-expanded="false" aria-controls="collapseExample"><i class="feather icon-search"></i></button>
			<a href="<?=BASE_URL?>/notice/add" class="btn btn-outline-primary" title="공지사항 등록"><i class="feather icon-edit"></i> 등록</a>
		</div>
	</div>
	<div class="collapse" id="search">
		<form method="get" class="card card-body shadow-sm bg-light pb-0 mb-3">
			<div class="row mb-3">
				<label for="search_word" class="col-md-2 col-form-label">검색어</label>
				<div class="col-md-6">
					<input type="text" name="search_word" id="search_word" class="form-control" value="<?=@$_GET['search_word']?>">
				</div>
				<div class="col-md"><button type="submit" class="btn btn-block btn-primary">검색</button></div>
			</div>
		</form>
	</div>
	<div class="row">
		<div class="col-lg">
			<form method="post" id="noticeControlForm" class="table-responsive scrollbar-macosx">
				<table class="table table-sm table-bordered table-hover text-center align-middle small text-nowrap">
					<thead class="table-light">
						<tr>
							<th class="text-nowrap" style="width:1%;">
								<div class="form-check mb-0 ml-1">
									<input class="form-check-input select-all" type="checkbox" id="select-all">
									<label class="form-check-label" for="select-all">
								</div>
							</th>
							<th class="text-nowrap" style="width:1%;">번호</th>
							<th>제목</th>
							<th class="text-nowrap" style="width:1%;">작성자</th>
							<th class="text-nowrap" style="width:1%;">날짜</th>
						</tr>
					</thead>
					<tbody>
<?
	if($notices){ // 공지사항이 있으면
		foreach($notices as $i => $nt){
			$link = BASE_URL.'/notice/view/'.$nt['idx'].'?search_word='.@$_GET['search_word'].'&page='.@$_GET['page'];
?>
						<tr<?if($notice && $notice['idx'] == $nt['idx']){?> class="table-warning"<?}?>>
							<td>
								<div class="form-check mb-0 ml-1">
									<input name="notice_idx[]" class="form-check-input select-each" type="checkbox" id="select-each<?=$nt['idx']?>" value="<?=$nt['idx']?>">
									<label class="form-check-label" for="select-each<?=$nt['idx']?>">
								</div>
							</td>
							<td class="position-relative text-nowrap text-center"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=$nt['idx']?></a></td>
							<td class="position-relative"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=$nt['notice_title']?></a></td>
							<td class="position-relative text-nowrap" style="width:1%;"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=$nt['user_name']?></a></td>
							<td class="position-relative text-nowrap" style="width:1%;"><a href="<?=$link?>" class="stretched-link text-decoration-none text-reset"><?=date('Y-m-d', $nt['time'])?></a></td>
						</tr>
<?
		}
	}else{ // 공지사항이 없으면
?>
						<tr>
							<td colspan="12" class="py-5 text-danger text-center">등록된 공지사항이 없습니다.</td>
						</tr>
<?
	}
?>
				</table>
			</form>

			<nav class="">
				<ul class="pagination pagination-sm justify-content-center">
<? // PAGING START
echo '<li class="page-item"><a href="?search_word='.@$_GET['search_word'].'&page=1" class="page-link"><i class="feather icon-chevron-left"></i></a></li>'; // 첫 페이지 
for($i=0;$i<$page_half;$i++){
	$j = $page - $page_half + $i;
	if($j > 0){ 
		echo '<li class="page-item"><a href="?search_word='.@$_GET['search_word'].'&page='.$j.'" class="page-link">'.$j.'</a>'; // 현재 페이지의 앞페이지들
	}
} 

echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>'; // 현재 페이지 

for($i=0;$i<$page_half;$i++){
	$j = $page + $i + 1;
	if($j <= $page_total){
		echo '<li class="page-item"><a href="?search_word='.@$_GET['search_word'].'&page='.$j.'" class="page-link">'.$j.'</a></li>'; // 현재 페이지의 뒷페이지들
	}
}

echo '<li class="page-item"><a href="?search_word='.@$_GET['search_word'].'&page='.$page_total.'" class="page-link"><i class="feather icon-chevron-right"></i></a></li>'; // 끝 페이지
// PAGING END
?>
				</ul>
			</nav>
		</div>
	</div>
</div>

<div id="footer-control" class="fixed-bottom bg-light border-top" style="display:none;">
	<div class="container-xxl p-3">
		<div class="row">
			<div class="col">
				<button type="submit" class="btn btn-danger btn-block" id="controlNoticeDeleteButton" form="noticeControlForm" formaction="<?=BASE_URL?>/notice/ajax_delete_notices"><i class="feather icon-trash"></i> 선택한 것을 삭제</button>
			</div>
		</div>
	</div>
</div>


<script>
$(function(){
	'use strict';

	$('.select-all').click(function(){
		$('.select-each').trigger('click');
	});

	$('.select-each').click(function(){
		var targets = $('.select-each:checked');
		if(targets.length > 0){
			$('#footer-control').show();
		}else{
			$('#footer-control').hide();
		}
	});

	$('#controlNoticeDeleteButton').click(function(event){
		event.preventDefault();

		var form_id = $(this).attr('form');
		var form = $('#'+form_id)[0];
		var action = $(this).attr("formaction");
		var method = $(form).attr("method");
		var formData = new FormData(form);

		var targets = $(form).find('.select-each:checked');
		if(targets.length < 1){
			const Toast = Swal.mixin({toast: true, position: 'center', showConfirmButton: false, timer: 1500});
			Toast.fire({icon: 'error', title: '선택된 항목이 없습니다.'});
			return false;
		}else{
			Swal.fire({
				icon: 'warning',
				title: '삭제',
				text: '정말 삭제하시겠습니까?',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: '예, 삭제합니다!',
				cancelButtonText: '아니오'
			}).then(function(result){
				if (result.value){
					$('#now-loading').show();
					var request = $.ajax({url: action, type: method, data: formData, processData: false, contentType: false, dataType: "json"});
					request.done(function(data){
						$('#now-loading').hide();
						if(data.result == 'true'){
							Swal.fire({icon: 'success', title: '성공', text: '공지사항이 삭제되었습니다.', timer: 1500})
								.then(function(){
									location.reload();
								});
						}else{
							Swal.fire({icon: 'error', title: '실패', text: data.message, timer: 1500});
						}
					});
					request.fail(function(){
						$('#now-loading').hide();
						Swal.fire({icon: 'error', title: '실패', text: '요청이 실패하였습니다. 관리자에게 문의해주세요.', timer: 1500});
					});
				}
			});
		}
	});

});
</script>