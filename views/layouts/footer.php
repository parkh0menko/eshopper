<footer id="footer"><!--Footer-->
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <p class="pull-left">Copyright © 2018</p>
            </div>
        </div>
    </div>
</footer><!--/Footer-->

<script src="/eshopper/template/js/jquery.js"></script>
<script src="/eshopper/template/js/bootstrap.min.js"></script>
<script src="/eshopper/template/js/jquery.scrollUp.min.js"></script>
<script src="/eshopper/template/js/price-range.js"></script>
<script src="/eshopper/template/js/jquery.prettyPhoto.js"></script>
<script src="/eshopper/template/js/main.js"></script>
<script>
	$(document).ready(function() {
		$(".add-to-cart").click(function() {
			var id = $(this).attr("data-id");
			$.post("cart/addAjax/"+id, {}, function(data) {
				$("#cart-count").html(data);
			});
			return false;
		});
	});
</script>
</body>
</html>