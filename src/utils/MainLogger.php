<?php
namespace utils;

class MainLogger extends \Thread{

    private $server;
    private $file;
    private $log;

    public function __construct(\Server $server){
        $this->server = $server;
        $this->file = $server->getLogFile();
        $this->log = [];
    }

    public function run(){
        while($this->server->isRunning()){
            if(count($this->log) !== 0){
                $tmp = (array)$this->log;
                foreach($tmp as $key => $log){
                    //echo $log;
                    $this->write($log);
                    unset($this->log[$key]);
                }
            }
            sleep(1);//or cpu thread used up....
        }
    }

    public function info($log){
        $log = TextFormat::AQUA . $this->getTime() . " " . TextFormat::WHITE . $this->getThread() . "->" . $log . PHP_EOL;
        $this->print($log);
    }

    public function success($log){
        $log = TextFormat::AQUA . $this->getTime() . " " . TextFormat::GREEN . $this->getThread() . "->" . $log . PHP_EOL;
        $this->print($log);
    }

    private function print($log){
        echo $log;
        $log = TextFormat::clean($log);
        $this->log[] = $log;
    }

    private function getTime(){
        return date('[G:i:s]');
    }

    private function getThread(){
        $thread = \Thread::getCurrentThread();
		if($thread === null){
			$threadName = "Server";
		}elseif($thread instanceof Thread or $thread instanceof Worker){
			$threadName = $thread->getThreadName();
		}else{
			$threadName = (new \ReflectionClass($thread))->getName();//getShortName()
		}
        //$class = str_replace("Server",TextFormat::LIGHT_PURPLE . "Server" . TextFormat::WHITE, $class);
   	    return "[" . $threadName . "/INFO]";
    }

    private function write($log){
        file_put_contents($this->file, $log, FILE_APPEND);
    }

}