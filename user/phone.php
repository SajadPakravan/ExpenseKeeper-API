<?php
include '_init_.php';

setMethod('POST');
authorization();
$phone = param('phone');

if (preg_match('/^09[0-9]{9}$/', $phone)) sendPhoneCode($phone);
setError(400, 'Invalid Phone');