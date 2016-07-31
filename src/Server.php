<?php
use utils\MainLogger;
use utils\Curl;
use login\LoginHandler;
use plugin\PluginManager;
use worker\MessageReceiver;
use worker\MessageSender;
use module\GetSelfInfo;
use module\GetRecentList;
class Server{

    const PLUGIN_DIR = 'plugins';
    const LOG_FILENAME = 'server.log';

    public $session;

    private $basedir;
    private $logger;
    private $pluginmanager;
    private $sender;
    private $receiver;

    public function __construct(){
        $this->basedir = '.';
        $this->logger = new MainLogger($this);
        $this->logger->start();
        $this->logger->info("正在启动服务端...");
        $this->logger->info("正在尝试登录WebQQ...");
        $this->session = (new LoginHandler($this))->login();
        $this->sender = new MessageSender($this);
        $this->receiver = new MessageReceiver($this);
        $this->logger->info("正在加载插件...");
        $this->pluginmanager = new PluginManager($this);
        $this->pluginmanager->load();
        $this->logger->info("正在加载消息收发接口...");
        $this->sender->start();
        $this->receiver->start();
        $this->logger->info("服务端启动完成!");
        while(true){
            $this->pluginmanager->doTick();
            sleep(1);
        }
    }

    public function getReceiver(){
        return $this->receiver;
    }

    public function getSender(){
        return $this->sender;
    }
    
    public function getLogger(){
        return $this->logger;
    }

    public function getLogFile(){
        return $this->getBaseDir().DIRECTORY_SEPARATOR.self::LOG_FILENAME;
    }

    public function isRunning(){
        return true;
    }

    public function getPluginManager(){
        return $this->pluginmanager;
    }

    public function getBaseDir(){
        return $this->basedir;
    }

}