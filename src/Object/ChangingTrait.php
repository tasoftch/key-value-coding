<?php
/**
*	TASoft Key-Value-Coding
*	@link https://www.tasoft.ch
*	@copyright 2017 - 2027 (c) TASoft Applications, Th. Abplanalp
*	@license MIT
*/
namespace TASoft\KVC\Object;
use TASoft\KVC\NotificationCenter;

/**
*	The changing trait implements the ChangingInterface for you.
*	Just use this trait with any object you want to enable changing observations.
*/
trait ChangingTrait {
	private $_kvo_changes = [];
	
/**
*	@inheritDoc
*/
	public function willChangeValueForKey($key) {
		if(!isset($this->_kvo_changes['keys'][$key])) {
			$value = $this->valueForKey($key);
			$this->_kvo_changes['keys'][$key]['old'] = $value;
		} else {
			if(!isset($this->_kvo_changes['keys'][$key]['initial']))
				$this->_kvo_changes['keys'][$key]['initial'] = $this->_kvo_changes['keys'][$key]['old'];
			$value = $this->valueForKey($key);
			$this->_kvo_changes['keys'][$key]['old'] = $value;
		}
	}

/**
*	Reset the stored changes of that object.
*	Only the keys of that object. Not keypaths to relationships.
*/
	public function resetChanges() {
		$this->_kvo_changes = [];
	}

/**
*	Get the changes commited while last reset or script start for
*	that object. As well, keypaths are not included.
*	@return array
*/
	public function getChanges() {
		return $this->_kvo_changes['keys'];
	}

/**
*	Returns true, if the object has any changes registered with it.
*	@return bool
*/
	public function hasChanges() {
		return count($this->_kvo_changes['keys']) > 0 ? true : false;
	}


/**
*	@inheritDoc
*/	
	public function didChangeValueForKey($key) {
		$value = $this->valueForKey($key);
		$this->_kvo_changes['keys'][$key]['new'] = $value;
		
		NotificationCenter::postNotification($key, $this, $this->_kvo_changes['keys'][$key]);
	}
	
/**
*	@inheritDoc
*/	
	public function willChangeValueForKeyPath($key) {
		if(!isset($this->_kvo_changes['paths'][$key])) {
			$value = $this->valueForKey($key);
			$this->_kvo_changes['paths'][$key]['old'] = $value;
		} else {
			if(!isset($this->_kvo_changes['paths'][$key]['initial']))
				$this->_kvo_changes['paths'][$key]['initial'] = $this->_kvo_changes['paths'][$key]['old'];
			$value = $this->valueForKey($key);
			$this->_kvo_changes['paths'][$key]['old'] = $value;
		}
	}

/**
*	@inheritDoc
*/
	public function didChangeValueForKeyPath($key) {
		$value = $this->valueForKey($key);
		$this->_kvo_changes['paths'][$key]['new'] = $value;
		
		NotificationCenter::postNotification($key, $this, $this->_kvo_changes['paths'][$key]);
	}
}