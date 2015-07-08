<?php

$mail = (array)@include('../mail.php');
$mail['encryption'] = '';
return $mail;