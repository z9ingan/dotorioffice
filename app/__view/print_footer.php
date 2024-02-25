
</div>
</td></tr></tbody>
<tfoot><tr><td><div class="print-footer-space"></div></td></tr></tfoot>
</table>

<header class="print-header">
	<div class="print-blank-out">&nbsp;</div>
	<div class="print-content">
		<div class="print-header-height d-flex align-items-center justify-content-between">
			<div><?=$mycompany['company_name']?></div>
			<div>&nbsp;</div>
			<div><span class="small font-weight-bold"><?=date('Y-m-d H:i:s')?></span></div>
		</div>
	</div>
	<div class="print-header-line print-blank-in"></div>
</header>

<footer class="print-footer">
	<div class="print-footer-line print-blank-in"></div>
	<div class="print-content">
		<div class="print-footer-height d-flex align-items-center justify-content-between">
			<div>&nbsp;</div>
			<div>&nbsp;</div>
			<div>재고관리시스템</div>
		</div>
	</div>
	<div class="print-blank-out">&nbsp;</div>
</footer>

</body>
<!-- 본문 종료 -->

<!-- Vendor Javascript -->
<script src="<?=VIEW_URL?>/vendor/popper/umd/popper.min.js"></script>
<script src="<?=VIEW_URL?>/vendor/bootstrap/js/bootstrap.min.js"></script>

</html>