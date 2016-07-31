<?php
namespace element;
use login\SavedSession;
use utils\Curl;

class User{

    private $uin;
    private $account;
    private $nick;
    private $card;

    public static $cache;
    
    public function __construct($uin){
        $this->uin = $uin;
        if(isset(static::$cache[$uin])){
            foreach(static::$cache[$uin] as $key => $value){
                $this->$key = $value;
            }
        }
    }

    public function getUin(){
        return $this->uin;
    }

    public function getNick($group = null){
        if($group instanceof Group){
            return $this->getCard($group);
        }else{
            return $this->getFriendNick();
        }
    }

    public function getCard(Group $group){
        if(isset($this->card[$group->getUin()])){
            return $this->card[$group->getUin()];
        }
        return false;
    }

    public function getFriendNick(){
        if(!isset($this->nick)){
            $json = (new Curl())->
            setUrl('http://s.web2.qq.com/api/get_friend_info2')->
            setReferer('http://s.web2.qq.com/proxy.html?v=20130916001')->
            setGet([
                'tuin' => $this->uin,
                'vfwebqq' => SavedSession::$vfwebqq,
                'clientid' => SavedSession::$clientid,
                'psessionid' => SavedSession::$psessionid,
            ])->
            setCookie(unserialize(SavedSession::$serialized))->
            returnHeader(false)->
            setTimeOut(5)->
            exec();
            $json = json_decode($json, true);
            if(isset($json['result']['nick'])){
                $nick = $json['result']['nick'];
                static::$cache[$this->uin]['nick'] = $nick;
                $this->nick = $nick;
            }else{
                return false;
            }
        }
        return $this->nick;
    }

    public function getAccount(){
        if(!isset($this->account)){
            $json = (new Curl())->
            setUrl('http://s.web2.qq.com/api/get_friend_uin2')->
            setReferer('http://s.web2.qq.com/proxy.html?v=20130916001')->
            setGet([
                'tuin' => $this->uin,
                'vfwebqq' => SavedSession::$vfwebqq,
            ])->
            setCookie(unserialize(SavedSession::$serialized))->
            returnHeader(false)->
            setTimeOut(5)->
            exec();
            $json = json_decode($json, true);
            $account = $json['result']['account'];
            static::$cache[$this->uin]['account'] = $account;
            $this->account = $account;
        }
        return $this->account;
    }

}