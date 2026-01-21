<?php
$i = 1;

$cfg['Servers'][$i]['auth_type'] = 'config';
$cfg['Servers'][$i]['host'] = getenv('PMA_HOST') ?: 'db';
$cfg['Servers'][$i]['port'] = getenv('PMA_PORT') ?: '3306';
$cfg['Servers'][$i]['user'] = getenv('PMA_USER') ?: 'rdv_user';
$cfg['Servers'][$i]['password'] = getenv('PMA_PASSWORD') ?: '';

$cfg['Servers'][$i]['AllowNoPassword'] = false;
