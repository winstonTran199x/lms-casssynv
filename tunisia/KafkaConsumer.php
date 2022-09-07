<?php
//define('CLI_SCRIPT', true);
//require('config.php');
//define('CLI_SCRIPT', true);
require('../../config.php');
require_once ('./classes/KafkaClient.php');
$client =  new \Kafka\KafkaClient();
$client->setTopicName('Default');
$client->consumerwithKey();
?>

