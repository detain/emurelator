<?php
namespace App\Command\PlatformCommand;

use App\App;
use CLIFramework\Command;
use CLIFramework\Formatter;
use CLIFramework\Logger\ActionLogger;
use CLIFramework\Debug\LineIndicator;
use CLIFramework\Debug\ConsoleDebug;

class ListCommand extends Command {
    public function brief() {
        return "List the sources.";
    }

    /** @param \GetOptionKit\OptionCollection $opts */
    public function options($opts) {
        parent::options($opts);
        $opts->add('v|verbose', 'increase output verbosity (stacked..use multiple times for even more output)')->isa('number')->incremental();
        $opts->add('f|format:', 'Format of the output [text(defaultt), table, xml, json, csv]')->isa('string')->validValues(['text','table','xml','json','csv','html','php']);
    }

    /** @param \CLIFramework\ArgInfoList $args */
    public function arguments($args) {
    }

    public function execute() {
        App::init($this->getOptions(), []);
        //$jsonOpts = JSON_PRETTY_PRINT |  JSON_UNESCAPED_UNICODE |  JSON_UNESCAPED_SLASHES;
        //file_put_contents($dataDir.'/emucontrolcenter.json', json_encode($data, $jsonOpts));
        $json = trim(file_get_contents(__DIR__.'/../../../../emurelation/sources.json'));
        $sources = json_decode($json, true);
        $this->getLogger()->writeln($json);

    }

}
