<?php
namespace module;
class MessageSender extends Module{
    private $messageid;

    public function __construct(\Server $server){
        parent::__construct($server);
        $this->messageid = mt_rand(101, 999) * 100000;
    }

    public function send($original, $message){
        switch($original['type']){
            case 'message':
                $this->sendUser($original['from'], $message);
                break;
            case 'group_message':
                $this->sendGroup($original['from'], $message);
                break;
        }
    }

    private function sendUser($uin, $content){
        do{
            $this->messageid++;
            $json = $this->getCurl()->
            setUrl('http://d1.web2.qq.com/channel/send_buddy_msg2')->
            setReferer('http://d1.web2.qq.com/proxy.html?v=20151105001')->
            setPost([
                'r' => json_encode([
                    'to' => $uin,
                    'content' => '["'.$content.'",["font",{"name":"宋体","size":10,"style":[0,0,0],"color":"000000"}]]',
                    'face' => 603,
                    'clientid' => $this->getSession()->clientid,
                    'msg_id' => $this->messageid,
                    'psessionid' => $this->getSession()->psessionid,
                ], JSON_FORCE_OBJECT)
            ])->
            setCookie($this->getSession()->getCookie())->
            returnHeader(false)->
            setTimeOut(5)->
            exec();
            $json = json_decode($json, true);
        }while(!(isset($json['errCode']) and (($json['errCode']) == 0)));
        return true;
    }

    private function sendGroup($uin, $content){
        do{
            $this->messageid++;
            $json = $this->getCurl()->
            setUrl('http://d1.web2.qq.com/channel/send_qun_msg2')->
            setReferer('http://d1.web2.qq.com/proxy.html?v=20151105001')->
            setPost([
                'r' => json_encode([
                    'group_uin' => $uin,
                    'content' => '["'.$content.'",["font",{"name":"宋体","size":10,"style":[0,0,0],"color":"000000"}]]',
                    'face' => 603,
                    'clientid' => $this->getSession()->clientid,
                    'msg_id' => $this->messageid,
                    'psessionid' => $this->getSession()->psessionid,
                ], JSON_FORCE_OBJECT)
            ])->
            setCookie($this->getSession()->getCookie())->
            returnHeader(false)->
            setTimeOut(5)->
            exec();
            $json = json_decode($json, true);
        }while(!(isset($json['errCode']) and (($json['errCode']) == 0)));
        return true;
    }

}