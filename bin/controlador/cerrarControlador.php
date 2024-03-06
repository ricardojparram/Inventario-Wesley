<?php 

if(isset($_SESSION['cedula'])){
	session_destroy();
	$script = '
		<script>
			localStorage.clear();
			window.location = "?url=login"
		</script>';
	die($script);
	
}

?>