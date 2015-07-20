<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 4:40 PM
 */

namespace TopFloor\Cds;

class CdsDependencyCollection {
	private $dependencies = array(
		'settings' => array(),
		'js' => array(),
		'css' => array(),
	);

	public function __construct($dependencies = array()) {
		foreach ($dependencies as $type => $typeDependencies) {
			$this->dependencies[$type] = $typeDependencies;
		}
	}

	public function getDependencies($type = null) {
		if ($type == null) {
			return $this->dependencies;
		}

		if (!isset($this->dependencies[$type])) {
			return array();
		}

		return $this->dependencies[$type];
	}

	public function setDependency($type, $index, $value) {
		if (is_null($value)) {
			$this->dependencies[$type][] = $index;
		} else {
			$this->dependencies[$type][$index] = $value;
		}
	}

	public function js($id = null, $path = null) {
		if (is_null($path)) {
			return $this->getDependencies('js');
		}

		$this->setDependency('js', $id, $path);
	}

	public function css($id = null, $path = null) {
		if (is_null($path)) {
			return $this->getDependencies('css');
		}

		$this->setDependency('css', $id, $path);
	}

	public function settings() {
		return $this->getDependencies('settings');
	}

	public function setting($key, $value = null) {
		if (is_null($value)) {
			if (isset($this->dependencies['settings'][$key])) {
				return $this->dependencies['settings'][$key];
			} else {
				return null;
			}
		}

		$this->setDependency('settings', $key, $value);
		return $value;
	}

	public function addDependencies($dependencies) {
		if (is_a($dependencies, 'CdsDependencyCollection')) {
			/** @var CdsDependencyCollection $dependencies */
			$dependencies = $dependencies->getDependencies();
		}

		foreach ($dependencies as $type => $typeDependencies) {
			foreach ($typeDependencies as $key => $value) {
				$this->setDependency($type, $key, $value);
			}
		}
	}
}