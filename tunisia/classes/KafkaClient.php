<?php


namespace Kafka;
use local_tunisia\task\sync_user_info_task;


class KafkaClient
{

    private $topicname;
    private $kafka_config= [
//    'bootstrap.servers'=> 'pkc-ldvr1.asia-southeast1.gcp.confluent.cloud:9092',
//       'security.protocol'=>'SASL_SSL',
//        'sasl.mechanism'=> 'PLAIN',
//        'sasl.username'=> 'EOKLEH5R5CO3F2C4',
//        'sasl.password'=> 'Wdj2zHdrqYGVh4w70cVKnFetJe3hEzxxbvAXafLSOO3/S6pDT5vgszNrNFaWfcps',
        'metadata.broker.list'=>'192.168.1.32:9092',
        'group.id'=>'myConsumerGroup',
        'auto.offset.reset'=> 'latest',
    ];
    public function setTopicName($topicname){
        $this->topicname=$topicname;
    }
    public function connect($config)
    {
        $conf = new \RdKafka\Conf();

        foreach ($config as $key=>$value)
        {
            $conf->set($key,$value);
        }
        return $conf;
    }

    public function producerWithKey($message,$key)
    {
        $connect = $this->connect($this->kafka_config);
        $produce = new \RdKafka\Producer($connect);
        $topic = $produce->newTopic($this->topicname);
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message, $key);
        $produce->flush(1000);
    }
    public function producerWithPartition($message,$partition,$key){
        $connect = $this->connect($this->kafka_config);
        $produce = new \RdKafka\Producer($connect);
        $topic = $produce->newTopic($this->topicname);
        $topic->produce($partition, 0, $message, $key);
        $produce->flush(1000);
    }
    public function consumerwithKey(array $key = null){
        $connect = $this->connect($this->kafka_config);
        $consumer = new \RdKafka\KafkaConsumer($connect);
        $consumer->subscribe([$this->topicname]);
        while (true){
            $message = $consumer->consume(1000);
            if($key != null)
            {
                if(in_array($message->key,$key))
                   $arr =  json_decode($message->payload,true);
                if($arr == null)
                {

                }else{
                    var_dump($arr["metadata.broker.list"]);

                }
            }else{
//                $arr =  json_decode($message->payload,true);
                $arr =[
                    'username'=>'potato',
                    'idnumber'=>'',
                    'firstname'=> 'potato',
                    'lastname'=> 'con cac',
                    'email'=> 'potato@email.com',
                    'phone1'=> '34767861278',
                    'city'=> 'tao o ha noi',
                    'status'=> 'SYNCING',
                ];
                if($arr == null)
                {

                }else{
                    sync_user_info_task::checking_user_and_upadte_status($arr);
                }
            }
            die();
        }
    }
    public function consumerwithPartition($number_partition = null){
        $connect = $this->connect($this->kafka_config);
        $consumer = new \RdKafka\KafkaConsumer($connect);
        $partition = new \RdKafka\TopicPartition($this->topicname,$number_partition);
        $consumer->assign([$partition]);
        while (true){
            $message = $consumer->consume(1000);
            var_dump($message->payload);
        }
    }
}
