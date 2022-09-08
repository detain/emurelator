<?php
namespace App\Command;

use CLIFramework\Command;

class GameCommand extends Command {
	public function brief() {
		return "External sources of data which we will map between each other.";
	}

	public function execute() {
        echo '
SYNTAX

emurelator.phar source <subcommand>

SUBCOMMANDS
	list                    displays a list of the current sources
	add                     adds a new source
    edit                    update the information on a source
    del                     remove a source

EXAMPLES
    <none yet>
';
	}
}
