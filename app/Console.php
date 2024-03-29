<?php
namespace App;

use CLIFramework\Application;
use App\App;
use App\Logger;

class Console extends Application
{
    const NAME = 'ProVirted';
    const VERSION = '2.0';

    public function init() {
        $this->enableCommandAutoload();
        parent::init();
        $this->topic('basic');
        $this->topic('examples');
        //App::setLogger($this->getLogger());
        $args = $_SERVER['argv'];
        array_shift($args);
        App::setLogger(new Logger());
        App::getLogger()->addHistory(['type' => 'program', 'text' => implode(' ', $args), 'start' => time()]);
        $minimumMemoryLimit = '1G';
        $minimumMemoryLimit = $this->getBytes($minimumMemoryLimit);
        $memoryLimit = $this->getBytes(ini_get('memory_limit'));
        if ($memoryLimit != -1 && $memoryLimit < $minimumMemoryLimit)
            ini_set('memory_limit', $minimumMemoryLimit);
    }

    public function finish() {
        parent::finish();
        if (App::getLogger()->isHistoryEnabled()) {
            $history = App::getLogger()->getHistory();
            if (count($history) > 1) {
                $history[0]['end'] = time();
                @mkdir($_SERVER['HOME'].'/.emurelator', 0750, true);
                $allHistory = file_exists($_SERVER['HOME'].'/.emurelator/history.json') ? json_decode(file_get_contents($_SERVER['HOME'].'/.emurelator/history.json'), true) : [];
                $allHistory[] = $history;
                file_put_contents($_SERVER['HOME'].'/.emurelator/history.json', json_encode($allHistory, JSON_PRETTY_PRINT));
            }
        }
    }

    
    /**
    * gets the value in bytes converted from a human readable string like 10G'
    * 
    * @param mixed $val the human readable/shorthand version of the value
    * @return int the value converted to bytes
    */
    public function getBytes($val) {
        $val = trim($val);
        if ($val == '-1')
            return -1;
        preg_match('/([0-9]+)[\s]*([a-zA-Z]+)/', $val, $matches);
        $value = (isset($matches[1])) ? intval($matches[1]) : 0;
        $metric = (isset($matches[2])) ? strtolower($matches[2]) : 'b';
        switch ($metric) {
            case 'tb':
            case 't':
                $value *= 1024;
            case 'gb':
            case 'g':
                $value *= 1024;
            case 'mb':
            case 'm':
                $value *= 1024;
            case 'kb':
            case 'k':
                $value *= 1024;
        }
        return $value;
    }

}
