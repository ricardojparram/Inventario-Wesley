<?php

if (isset($_SESSION['cedula'])) {
	session_destroy();
	$script = '
		<script>
			localStorage.clear();
			window.location = "login"
		</script>';
	die($script);
}
