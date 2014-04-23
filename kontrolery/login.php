<?php

use Nette\Templating\FileTemplate;
$template = new FileTemplate('sablony/login.latte');
$template->title = "přihlášení";
if ($_POST) {
	if ($_POST["name"]) {
		if ($_POST["password"]) {
			if (checkLogin($_POST["name"], $_POST["password"])) {
				$_SESSION["logged"] = true;
			} else {
				$template->error = "Neplatná kombinace!";
			}
		} else {
			$template->error = "Nezadali jste heslo!";
		}
	} else {
		$template->error = "Nezadali jste jméno!";
	}
}

if (isset($_SESSION["logged"]) && $_SESSION["logged"] === true) {
	header("Location: administrace");
}



function checkLogin($name,$pass){
	$logins = array("admin" => "administrator",
			"mates" => "mitas");
	return isset($logins[$name]) && $logins[$name] == $pass;
}