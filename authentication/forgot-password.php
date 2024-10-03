<?php
include '_init_.php';

setMethod('POST');
$data = param('data');

if (checkEmail($data)) sendEmailCode($data);
if ((checkPhone($data))) sendPhoneCode($data);
setError(400, 'Invalid Input');