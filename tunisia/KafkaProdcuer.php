<?php
define('CLI_SCRIPT', true);
require('config.php');
require_once ('./classes/KafkaClient.php');
$conf =  new \Kafka\KafkaClient();
$conf->setTopicName('Default');
$message_body= [
    'username'=>'pine',
    'idnumber'=>'72',
    'firstname'=> 'pine',
    'lastname'=> 'apple',
    'email'=> 'pineapple@email.com',
    'phone1'=> '123123123',
    'status'=> 'SYNCING',
    'action'=> 'u',
];

$key_message= 'u';
$conf->producerWithKey(json_encode($message_body),$key_message);

