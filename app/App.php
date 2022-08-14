<?php
namespace App;

use App\XmlToArray;
use App\Os\Os;

/**
* Provides OOP interface to virtualization technologies
*/
class App
{
	/** @var \App\Logger */
	protected static $logger;
	/** @var array */
	protected static $args;
	/** @var \GetOptionKit\OptionCollection */
	protected static $opts;

	/**
	* @param \App\Logger $logger
	*/
	public static function setLogger($logger) {
		self::$logger = $logger;
	}

	/**
	* @return \App\Logger
	*/
	public static function getLogger() {
		return self::$logger;
	}

	/**
	* @param \GetOptionKit\OptionCollection $opts
	* @param array $args
	*/
	public static function init($opts, array $args) {
		self::$opts = $opts;
		self::$args = $args;
		if (array_key_exists('verbose', self::$opts->keys)) {
			self::getLogger()->info("verbosity increased by ".self::$opts->keys['verbose']->value." levels");
			self::getLogger()->setLevel(self::getLogger()->getLevel() + self::$opts->keys['verbose']->value);
		}
	}


	/**
	* returns an array containing information about the host server, vlans, and vps's
	*
	* @return array the host info
	*/
	public static function getHostInfo() {
		$response = trim(self::runCommand('curl -s '.escapeshellarg(self::getUrl().'?action=get_info')));
		$host = json_decode($response, true);
		if (!is_array($host) || !isset($host['vlans'])) {
			self::getLogger()->error("invalid response getting host info:".$response);
			return false;
		}
		/* $vps = {
			"id": "2324459",
			"hostname": "vps2324459",
			"vzid": "vps2324459",
			"mac": "00:16:3e:23:77:eb",
			"ip": "208.73.202.209",
			"status": "active",
			"server_status": "running",
			"vnc": "79.156.208.231"
		} */

		@mkdir($_SERVER['HOME'].'/.emurelator', 0750, true);
		file_put_contents($_SERVER['HOME'].'/.emurelator/host.json', $response);
		return $host;
	}

	/**
	* converts an order id into a mac address
	*
	* @param int $id
	* @param bool $useAll
	* @return string
	*/
	public static function convertIdToMac($id, $useAll) {
		$prefix = $useAll == true ? '00:0C:29' : '00:16:3E';
		$suffix = strtoupper(sprintf("%06s", dechex($id)));
		$mac = $prefix.':'.substr($suffix, 0, 2).':'.substr($suffix, 2, 2).':'.substr($suffix, 4, 2);
		return $mac;
	}

	public static function getHistoryChoices() {
		$return = self::getLogger()->getHistory();
		array_unshift($return, 'last');
	}

	/**
	* runs a commnand
	*
	* @param string $cmd command to run
	* @param int $return store the return value
	* @param false|int $timeout false or timeout in seconds
	* @return string stdout.stderr text
	*/
	public static function runCommand($cmd, &$return = 0, $timeout = false) {
		$descs = [
			0 => ['pipe','r'],
			1 => ['pipe','w'],
			2 => ['pipe','w']
		];
		$stdout = '';
		$stderr = '';
		$proc = proc_open($cmd, $descs, $pipes);
		if (is_resource($proc)) {
			if ($timeout !== false) {
				stream_set_timeout($pipes[1], $timeout);
				stream_set_timeout($pipes[2], $timeout);
			}
			while (!feof($pipes[1])) {
				$stdout .= fgets($pipes[1]);
				$info = stream_get_meta_data($pipes[1]);
				if ($info['timed_out'] == true) {
					echo 'Connection timed out!';
					break;
				}
			}
			while (!feof($pipes[2])) {
				$stderr .= fgets($pipes[2]);
				$info = stream_get_meta_data($pipes[2]);
				if ($info['timed_out'] == true) {
					echo 'Connection timed out!';
					break;
				}
			}
			fclose($pipes[0]);
			fclose($pipes[1]);
			fclose($pipes[2]);
			$status = proc_get_status($proc);
			$retVal = proc_close($proc);
			$return = $status['running'] ? $retVal : $status['exitcode'];
		} else {
			$stderr = 'couldnt run';
			$return = 1;
		}
		self::getLogger()->info2('cmd:'.$cmd);
		self::getLogger()->debug('out:'.$stdout);
		$history = [
			'type' => 'command',
			'command' => $cmd,
			'output' => $stdout,
			'return' => $return
		];
		if ($stderr != '') {
			$history['error'] = $stderr;
			self::getLogger()->debug('error:'.$stderr);
		}
		/*
		$output = [];
		exec($cmd, $output, $return);
		self::getLogger()->indent();
		foreach ($output as $line)
			self::getLogger()->debug('out:'.$line);
		self::getLogger()->unIndent();
		self::getLogger()->debug('exit:'.$return);
		$response = implode("\n", $output);
		*/
		self::getLogger()->addHistory($history);
		return $stdout.$stderr;
	}
}
