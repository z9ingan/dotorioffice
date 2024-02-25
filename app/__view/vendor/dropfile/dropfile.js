$(function(){

/* 예
		<div class="dropfile-area">
			<span class="dropfile-btn">파일 선택</span>
			<span class="dropfile-msg">또는 파일을 드래그해서 넣어주세요.</span>
			<input class="dropfile" type="file" multiple data-selected="개의 파일이 선택되었습니다.">
		</div>
*/
	
	var $fileInput = $('.dropfile');

	// highlight drag area
	$fileInput.on('dragenter focus click', function() {
		$(this).parent('.dropfile-area').addClass('is-active');
	});

	// back to normal state
	$fileInput.on('dragleave blur drop', function() {
		$(this).parent('.dropfile-area').removeClass('is-active');
	});

	// change inner text
	$fileInput.on('change', function() {
		var filesCount = $(this)[0].files.length;
		var $textContainer = $(this).prev();

		if (filesCount === 1) {
			// if single file is selected, show file name
			var fileName = $(this).val().split('\\').pop();
			$textContainer.text(fileName);
		} else {
			// otherwise show number of files
			$textContainer.text(filesCount + ' ' + $(this).data('selected'));
		}
	});

});