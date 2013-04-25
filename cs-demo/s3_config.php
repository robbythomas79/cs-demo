<?php
// Bucket Name
$bucket="robs-demo";
if (!class_exists('S3'))require_once('S3.php');
			
//AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAIIARUCXDTJ2C4QOA');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');
			
//instantiate the class
$s3 = new S3(awsAccessKey, awsSecretKey);

$s3->putBucket($bucket, S3::ACL_PUBLIC_READ);

?>
