<div class="navbar navbar-fixed-bottom navbar-default">
 <div class="container-fluid">
  <div>&copy; 2014-<?=date("Y");?> <a target="_blank" href="http://www.valligator.com">Valligator.com</a> - All rights reserved.</div>
 </div>
</div>
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript">
var token = '<?php echo $token;?>';
var tokenURL = '<?php echo urlencode($token);?>';
$(document).ready(function() {
<?php if ($phpSelf == 'module.php') { ?>
 var viewModListAjax = $.ajax({
  method: "POST",
  url: "ajax_loadData.php",
  data: { action: "viewModList", token: tokenURL },
  dataType: "json"
 });
 viewModListAjax.success(function(data){
  $('#divModView').html('!!!');
 });
<?php } ?>
});
</script>
</body>
</html>
