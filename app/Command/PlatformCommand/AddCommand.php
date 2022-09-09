<?php
namespace App\Command\PlatformCommand;

use App\App;
use CLIFramework\Command;
use CLIFramework\Formatter;
use CLIFramework\Logger\ActionLogger;
use CLIFramework\Debug\LineIndicator;
use CLIFramework\Debug\ConsoleDebug;

class AddCommand extends Command {
    public function brief() {
        return "Add a company.";
    }

    /** @param \GetOptionKit\OptionCollection $opts */
    public function options($opts) {
        parent::options($opts);
        $opts->add('v|verbose', 'increase output verbosity (stacked..use multiple times for even more output)')->isa('number')->incremental();
        $opts->add('f|format:', 'Format of the output [text(defaultt), table, xml, json, csv]')->isa('string')->validValues(['text','table','xml','json','csv','html','php']);
    }

    /** @param \CLIFramework\ArgInfoList $args */
    public function arguments($args) {
        $args->add('company')->desc('name of the company')->isa('string');
        $args->add('name')->desc('name of the platform')->isa('string');
    }

    public function execute($company, $name) {
        App::init($this->getOptions(), ['name' => $name]);
        $data = App::loadSource('local');
        $id = $company.' '.$name;
        if (!isset($data['platforms'][$id])) {
            $data['platforms'][$id] = [
                'id' => $id,
                'name' => $name,
                'company' => $company,
                'matches' => []
            ];
            App::saveSource('local', $data);
            $this->getLogger()->writeln('Added Platform '.$id);
        } else {
            $this->getLogger()->writeln('Platform '.$id.' already exists!');
        }
    }
}
