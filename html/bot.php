<div class="navbar navbar-fixed-bottom navbar-default">
 <div class="container-fluid">
  <div>&copy; 2014-<?=date("Y");?> <a target="_blank" href="http://www.valligator.com">Valligator.com</a> - All rights reserved.</div>
 </div>
</div>
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript">
var token = '<?php echo $token;?>';
$(document).ready(function() {
<?php if ($phpSelf == 'module.php') { ?>
 $.ajax({
  method: "POST",
  url: "ajax_loadData.php",
  data: { action: "viewModList", token: token },
  dataType: "json"
 });
<?php } ?>
});
</script>
</body>
</html>
