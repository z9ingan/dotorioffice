<div class="container-xxl py-3" style="height:calc(100vh - 3.5rem);">
	<div class="d-flex align-items-center justify-content-between mb-3">
		<h2 class="h4 font-weight-bold mb-0"><i class="feather icon-message-circle"></i> 공지사항</h2>
		<div>
			<button class="history-back btn btn-outline-dark" title="뒤로가기"><i class="feather icon-arrow-left"></i></button>
			<a href="<?=BASE_URL?>/notice/edit/<?=$notice['idx']?>" class="btn btn-outline-warning" title="수정"><i class="feather icon-edit-2"></i></a>
			<button class="btn btn-outline-danger" id="noticeDeleteButton" title="삭제"><i class="feather icon-trash"></i></button>
		</div>
	</div>
	<div>
		<h3 class="h1"><?=$notice['notice_title']?></h3>
		<div class="mb-3 small text-muted"><i class="feather icon-user"></i> <?=$notice['user_name']?> / <i class="feather icon-calendar"></i> <?=date('Y-m-d H:i:s', $notice['time'])?></div>
		<div class="card card-body mb-3"><?=nl2br($notice['notice_memo'])?></div>
	</div>
<?if($notice['noticefiles']){?>
	<div>
		<h4 class="h6 font-weight-bold"><i class="feather icon-save"></i> 첨부파일</h4>
		<div class="row row-cols-xl-6 row-cols-lg-5 row-cols-md-4 row-cols-sm-3 row-cols-2 g-3">
<?foreach($notice['noticefiles'] as $ntf){?>
			<div class="col">
				<div class="card shadow-sm h-100">
<?if(isset($ntf['thumb']) && file_exists($ntf['thumb'])){?>
					<a href="<?=BASE_URL?>/notice/file_image_noticefile/<?=$ntf['idx']?>" class="d-block" data-type="image" data-fancybox="noticefile" data-caption="<?=$ntf['filename']?>">
						<img src="<?=BASE_URL?>/notice/file_thumb_noticefile/<?=$ntf['idx']?>" class="card-img-top" alt="<?=$ntf['filename']?>">
					</a>
<?}else{?>
					<svg width="100%" height="40" class="card-body p-0" alt="<?=$ntf['filename']?>">
						<rect width="100%" height="100%" fill="#cccccc"></rect>
						<text x="50%" y="50%" fill="white" alignment-baseline="middle" text-anchor="middle"><?=$ntf['fileext']?></text>
					</svg>
<?}?>
					<div class="card-footer bg-white p-2 text-nowrap overflow-hidden"><a href="<?=BASE_URL?>/notice/file_download_noticefile/<?=$ntf['idx']?>" target="_blank" class="text-reset text-decoration-none font-weight-bold small"><i class="feather icon-download-cloud"></i> <?=$ntf['filename']?></a></div>
				</div>
			</div>
<?}?>
		</div>
	</div>
<?}?>
</div>

<script>
$(function(){
	'use strict';

	$('#noticeDeleteButton').click(function(event){
		event.preventDefault();

		var notice_idx = $(this).data('noticeIdx');

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
				var request = $.post('<?=BASE_URL?>/notice/ajax_delete_notices', {'category_idx': category_idx});
				request.done(function(data){
					if(data.result == 'true'){
						Swal.fire({icon: 'success', title: '성공', text: '공지사항이 삭제되었습니다.', timer: 1500})
							.then(function(){
								location.href = '<?=BASE_URL?>/notice';
							});
					}else{
						Swal.fire({icon: 'error', title: '실패', text: data.message, timer: 1500});
					}
				});
				request.fail(function(){
					Swal.fire({icon: 'error', title: '실패', text: '요청이 실패하였습니다. 관리자에게 문의해주세요.', timer: 1500});
				});
			}
		});
	});

});
</script>