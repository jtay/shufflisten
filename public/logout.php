<?php

session_start();
$_SESSION = NULL;
unset($_SESSION);
session_destroy();

header('Location: /');
