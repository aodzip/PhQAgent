<?php
namespace plugin;
use element\Message;
class PluginManager{

    private $server;
    private $plugins = [];
    private $ticks = [];
    private $message;

    public function __construct(\Server $server){
        $this->server = $server;
        $this->message = [];
    }

    public function getMessage(){
        return $this->message;
    }

    public function doTick(){
        foreach($this->ticks as $plugin){
            $plugin->onTick();
        }
        $msgs = $this->server->getReceiver()->getMessage();
        if(count($msgs) !== 0){
            $tmp = (array)$msgs;
            foreach($tmp as $key => $msg){
                foreach($this->plugins as $plugin){
                    $plugin->onReceive(new Message(unserialize($msg)));
                }
                $this->server->getReceiver()->delMessage($key);
            }
        }
    }

    public function load(){
        $dir = \Server::PLUGIN_DIR;
        if(!file_exists($dir)){
            mkdir($dir);
        }
        $dir_array = scandir($dir);
        foreach($dir_array as $file){
            $pre = explode('.', $file);
            if(isset($pre[1])){
                if($pre[1] == 'php'){
                    $this->server->getLogger()->info("尝试加载插件: {$pre[0]}");
                    include($this->server->getBaseDir().DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR."$file");
                    $plg_class = "plugin\\{$pre[0]}";
                    $plugin = new $plg_class($this->server);
                    $this->plugins[$pre[0]] = $plugin;
                    $plugin->onLoad();
                }
            }
        }
    }

}