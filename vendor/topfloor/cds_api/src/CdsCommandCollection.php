<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 4:40 PM
 */

namespace Cds;


use Cds\CdsCommands\CdsCommand;

class CdsCommandCollection {
	private $commands = array();

	public function getCommands() {
		return $this->commands;
	}

	public function addCommand(CdsCommand $command) {
		$this->commands[] = $command;
	}

	/*
	 * Execute all commands in this list and return the concatenated results
	 */
	public function execute() {
		$output = '';

		/** @var CdsCommand $command */
		foreach ($this->commands as $command) {
			$output .= $command->execute() . "\n";
		}

		return $output;
	}

	public function getDependencies() {
		$dependencies = new CdsDependencyCollection();

		/** @var CdsCommand $command */
		foreach ($this->commands as $command) {
			foreach ($command->getDependencies() as $commandDependencies) {
				$dependencies->addDependencies($commandDependencies);
			}
		}

		return $dependencies;
	}
}