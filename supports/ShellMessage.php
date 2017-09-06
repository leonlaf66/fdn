<?php
namespace common\supports;

class ShellMessage extends \yii\base\Component
{
    public $commandRootDir = '';

    public function send($command)
    {
        $data = [
            'uid' => 'shell',
            'command' => 'php '.$this->commandRootDir.'/yii '.$command
        ];

        // 建立socket连接到内部推送端口
        $client = stream_socket_client('tcp://127.0.0.1:5678', $errno, $errmsg, 1);
        // 发送数据，注意5678端口是Text协议的端口，Text协议需要在数据末尾加上换行符
        return fwrite($client, json_encode($data)."\n");
    }
}