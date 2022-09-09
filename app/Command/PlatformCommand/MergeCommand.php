<?php
namespace App\Command\PlatformCommand;

use App\App;
use CLIFramework\Command;
use CLIFramework\Formatter;
use CLIFramework\Logger\ActionLogger;
use CLIFramework\Debug\LineIndicator;
use CLIFramework\Debug\ConsoleDebug;

class MergeCommand extends Command {
    public function brief() {
        return "Merge a company.";
    }

    /** @param \GetOptionKit\OptionCollection $opts */
    public function options($opts) {
        parent::options($opts);
        $opts->add('v|verbose', 'increase output verbosity (stacked..use multiple times for even more output)')->isa('number')->incremental();
        $opts->add('f|format:', 'Format of the output [text(defaultt), table, xml, json, csv]')->isa('string')->validValues(['text','table','xml','json','csv','html','php']);
    }

    /** @param \CLIFramework\ArgInfoList $args */
    public function arguments($args) {
        $args->add('from')->desc('name of the platform to copy the data from and ultimate remove')->isa('string');
        $args->add('to')->desc('name of the platform to store the combined data of both')->isa('string');
    }

    public function execute($from, $to) {
        App::init($this->getOptions(), ['from' => $from, 'to' => $to]);
        $data = App::loadSource('local');
        if (isset($data['platforms'][$from])) {
            if (isset($data['platforms'][$to])) {
                if (isset($data['platforms'][$from]['company']) && !isset($data['platforms'][$to]['company'])) {
                    $this->getLogger()->writeln('Added company '.$data['platforms'][$from]['company']);
                    $data['platforms'][$id]['company'] = $data['platforms'][$from]['company'];
                }
                if (isset($data['platforms'][$from]['altNames']) && count($data['platforms'][$from]['altNames']) > 0) {
                    if (!isset($data['platforms'][$to]['altNames'])) {
                        $data['platforms'][$to]['altNames'] = [];
                    }
                    foreach ($data['platforms'][$from]['altNames'] as $name) {
                        if (!in_array($name, $data['platforms'][$to]['altNames'])) {
                            $this->getLogger()->writeln('Added altName '.$name.' to '.$to);
                            $data['platforms'][$to]['altNames'][] = $name;
                        }
                    }
                }
                if (isset($data['platforms'][$from]['matches']) && count($data['platforms'][$from]['matches']) > 0) {
                    if (!isset($data['platforms'][$to]['matches'])) {
                        $data['platforms'][$to]['matches'] = [];
                    }
                    foreach ($data['platforms'][$from]['matches'] as $name => $matches) {
                        if (!isset($data['platforms'][$to]['matches'][$name])) {
                            $data['platforms'][$to]['matches'][$name] = [];
                        }
                        foreach ($matches as $match) {
                            if (!in_array($match, $data['platforms'][$to]['matches'][$name])) {
                                $this->getLogger()->writeln('Added match for '.$name.' of '.$match.' to '.$to);
                                $data['platforms'][$to]['matches'][$name][] = $match;
                            }
                        }
                    }
                }
                unset($data['platforms'][$from]);
                App::saveSource('local', $data);
                $this->getLogger()->writeln('Mergeed Platform '.$from.' into '.$to);
            } else {
                $this->getLogger()->writeln('Platform '.$to.' does not exit!');
            }
        } else {
            $this->getLogger()->writeln('Platform '.$from.' does not exit!');
        }
    }
}
