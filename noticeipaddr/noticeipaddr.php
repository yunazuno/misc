#!/usr/bin/php
<?php
  /*
   * DiCE的スクリプト
   * IPアドレス変更をメール通知&DDNS更新
   * 要cron設定
   */

require('Mail.php');

$headers['From']    = 'me@example.com';
$headers['To']      = 'me@example.com';
$headers['Subject'] = 'IP address changed';

$mail_options = array(
    'host'      => 'be55.com',
    'port'      => 587, 
    'auth'      => false,
//    'username'  => '',
//    'password'  => '',
//    'localhost' => 'localhost'
);


$url = 'http://www.example.com/echoipaddr.php';
$cache = dirname(__FILE__) . '/noticeipaddr.txt';

$echoIP = @fopen($url, 'r');

if ($echoIP === FALSE)
    exit('Couldn\'t open: ' . $url);

$globalIP = trim(fgets($echoIP));

if ((preg_match('/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$/', $globalIP) !== 1))
    exit('Invalid response.');

echo 'Global IP address: ' . $globalIP ."\n";

$cacheIP = @fopen($cache, "r");

if ($cacheIP === FALSE)
{
    renewIPAddress($globalIP, $cache);
    exit(0);
}

$oldIP = trim(fgets($cacheIP));

fclose($cacheIP);

if ($oldIP != $globalIP)
{
    renewIPAddress($globalIP, $cache);
    exit(0);
}

function renewIPAddress($ipaddr, $cache)
{
    global $recipients, $mail_options, $headers;
    print "Renew IP Address...\n";

    $fp = fopen($cache, 'w');

    fwrite($fp, $ipaddr);

    $mail_object =& Mail::factory("SMTP",$mail_options);

    $result = $mail_object->send($recipients, $headers, $ipaddr);

    if (PEAR::isError($result)) {
        die($result->getMessage());
    }

    system('wget -q -O - \'http://ieserver.net/cgi-bin/dip.cgi?username=HOGEHOGE&domain=dip.jp&password=FUGAFUGA&updatehost=1\'');
}

