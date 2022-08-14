<?php
namespace App\Command;

use App\App;
use CLIFramework\Command;
use CLIFramework\Formatter;
use CLIFramework\Logger\ActionLogger;
use CLIFramework\Debug\LineIndicator;
use CLIFramework\Debug\ConsoleDebug;

class PlatformCommand extends Command {
	public function brief() {
		return "CD Drive/Image functionality";
	}
}
