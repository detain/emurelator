<?php
namespace App\Command\EmulatorCommand;

use App\App;
use CLIFramework\Command;
use CLIFramework\Formatter;
use CLIFramework\Logger\ActionLogger;
use CLIFramework\Debug\LineIndicator;
use CLIFramework\Debug\ConsoleDebug;

class AddCommand extends Command {
    public function brief() {
        return "Add a emulator.";
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
        $data = App::loadSource('local');
        if (!isset($data['emulators'][$name])) {
            $data['emulators'][$name] = [
                'id' => $name,
                'shortName' => $name,
                'name' => $name,
                'platforms' => [],
                'matches' => []
            ];
            App::saveSource('local', $data);
            $this->getLogger()->writeln('Added Emulator '.$name);
        } else {
            $this->getLogger()->writeln('Emulator '.$name.' already exists!');
        }
    }
}
