<?php
namespace App\Command;

use CLIFramework\Command;

class EmulatorCommand extends Command {
	public function brief() {
		return "Prepairs and works with a source based version of the project (instead of the phar)";
	}

	public function execute() {
        echo '
SYNTAX

emurelator.phar vnc <subcommand>

SUBCOMMANDS
	secure [--dry]            removes old and bad entries to maintain security
	setup <vzid> [ip]         create a new mapping
	remove <vzid>             remove a mapping
	restart                   restart the xinetd service
	rebuild [--dry]           removes old and bad entries to maintain security, and recreates all port mappings

EXAMPLES
	emurelator.phar vnc setup vps4000 8.8.8.8
	emurelator.phar vnc remove vps4000
	emurelator.phar vnc secure
	emurelator.phar vnc restart
	emurelator.phar vnc rebuild --dry
	emurelator.phar vnc rebuild
';
	}
}
