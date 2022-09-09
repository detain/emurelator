<?php
namespace App\Command\CompanyCommand;

use App\App;
use CLIFramework\Command;
use CLIFramework\Formatter;
use CLIFramework\Logger\ActionLogger;
use CLIFramework\Debug\LineIndicator;
use CLIFramework\Debug\ConsoleDebug;

class DelCommand extends Command {
    public function brief() {
        return "Deletes a company.";
    }

    /** @param \GetOptionKit\OptionCollection $opts */
    public function options($opts) {
        parent::options($opts);
        $opts->add('v|verbose', 'increase output verbosity (stacked..use multiple times for even more output)')->isa('number')->incremental();
        $opts->add('f|format:', 'Format of the output [text(defaultt), table, xml, json, csv]')->isa('string')->validValues(['text','table','xml','json','csv','html','php']);
    }

    /** @param \CLIFramework\ArgInfoList $args */
    public function arguments($args) {
        $args->add('name')->desc('name of the company')->isa('string');
    }

    public function execute($name) {
        App::init($this->getOptions(), ['name' => $name]);
        $local = App::loadSource('local');
        if (isset($local['companies'][$name])) {
            unset($local['companies'][$name]);
            App::saveSource('local', $local);
            $this->getLogger()->writeln('Deleted company '.$name);
        } else {
            $this->getLogger()->writeln('Company '.$name.' does not exist!');
        }
    }
}
