#!/usr/bin/env php

<?php
if (empty($argv[1]) || empty($argv[2])) {
    echo "Missing arguments. First argument is user, second argument is domain\n";
    exit(0);
}

$user = $argv[1];
$domain = $argv[2];

define('NGINX_AVAILABLE_DIR', '/opt/nginx/conf/site-available/');
define('NGINX_ENABLED_DIR', '/opt/nginx/conf/site-enabled/');
define('PHP_POOLS_DIR', '/opt/php/etc/pools/');

define('PHP_TPL', 'php-pool.conf.tpl');
define('NGINX_TPL', 'nginx-site.conf.tpl');

// create new php pool
$poolFileName = $domain . '.conf';
$poolTpl = file_get_contents(PHP_TPL);
$poolContent = str_replace('{user}', $user, $poolTpl);
$poolContent = str_replace('{domain}', $domain, $poolContent);
file_put_contents(PHP_POOLS_DIR . $poolFileName, $poolContent);

echo 'New PHP pool is created: ' . $poolFileName;
echo "\n";
echo 'Please reload or restart PHP service' . "\n";

// create new nginx site
$siteFileName = $domain . '.conf';
$siteTpl = file_get_contents(NGINX_TPL);
$siteContent = str_replace('{user}', $user, $siteTpl);
$siteContent = str_replace('{domain}', $domain, $siteContent);
file_put_contents(NGINX_AVAILABLE_DIR . $siteFileName, $siteContent);
chdir(NGINX_ENABLED_DIR);
symlink('../site-available/' . $siteFileName, $siteFileName);

echo 'New nginx site is created: ' . $siteFileName;
echo "\n";
echo "Please reload or restart nginx service\n";
