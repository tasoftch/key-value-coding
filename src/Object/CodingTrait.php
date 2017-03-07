<?php
/**
*	TASoft Key-Value-Coding
*	@link https://www.tasoft.ch
*	@copyright 2017 - 2027 (c) TASoft Applications, Th. Abplanalp
*	@license MIT
*/
namespace TASoft\KVC\Object;

use TASoft\KVC\KeyPathHelperClass;

/**
*	The CodingTrait implements the Object\CodingInterface and enabled the key-value
*	coding support for your object. Just use this trait with any object you want to
*	access via keys and keypaths.
*	
*	The CodingTrait override __get and __set to provide the key-value mechanism.
*	The final getters and setters are get{key} and set{key}.
*	
*	The CodingTrait also implements the ArrayAccess interface to support
*	keyPaths via array access.
*	
*	If your object implements ChangingInterface or uses ChangingTrait, every
*	setValueForKey or setValueForKeyPath will notify the object itself that
*	values changed.
*/

trait CodingTrait {
	private $_cachedKeys = [];
	
/**
*	A key-value-compliant trait requires to know, which keys your object supports.
*	The default implementation looks for class methods starting with get* and set*.
*	@param recache bool If true, will reload the keys.
*	@return array
*/
	public function getDefinedKeys($recache = false) {
		// If your objects keys changed, call this method manually by passing true to recache the keys.
		
		if(empty($this->_cachedKeys) || $recache) {
			$mthds = get_class_methods($this);
			$getters = $setters = [];
			
			foreach($mthds as $mt) {
				if(preg_match("/^(get|set)([a-z0-9_]+)$/i", $mt, $ms)) {
					if($ms[1] == 'get')
						$getters[] = lcfirst($ms[2]);
					if($ms[1] == 'set')
						$setters[] = lcfirst($ms[2]);
				}
			}
			$this->_cachedKeys = array_intersect($setters, $getters);		
		}
		
		return $this->_cachedKeys;
	}

/**
*	@inheritDoc
*/
	public function valueForKey($key) {
		if($this->_checkKey($key)) {
			$getter = "get".ucfirst($key);
			return call_user_func([$this, $getter]);
		}
		return $this->valueForUndefinedKey($key);
	}
	
/**
*	@inheritDoc
*/
	public function valueForKeyPath($keyPath) {
		$obj = KeyPathHelperClass::traceKeyPathWithObject($keyPath, $this, $key);
		
		if(is_object($obj))
			return $obj->valueForKey($key);
		return NULL;
	}
	
/**
*	@inheritDoc
*/
	public function valueForUndefinedKey($key) {
		// The default implementation does not allow to access undefined keys.
		// But you can override this method to specify custom actions.
		throw new \TASoft\KVC\Exception("Object `".get_class($this). "` is not key-value compliant for key `$key`", 191);
	}
	
/**
*	@inheritDoc
*/
	public function setValueForKey($value, $key) {
		if($this->_checkKey($key)) {
			$setter = "set".ucfirst($key);
			
			if(method_exists($this, 'willChangeValueForKey'))
				$this->willChangeValueForKey($key);
				
			call_user_func([$this, $setter], $value);
			
			if(method_exists($this, 'didChangeValueForKey'))
				$this->didChangeValueForKey($key);
			return;
		}
		$this->setValueForUndefinedKey($value, $key);
	}
	
/**
*	@inheritDoc
*/
	public function setValueForKeyPath($value, $keyPath) {
		$obj = KeyPathHelperClass::traceKeyPathWithObject($keyPath, $this, $key);
		if(is_object($obj)) {
			if(method_exists($this, 'willChangeValueForKeyPath'))
				$this->willChangeValueForKeyPath($key);
			$obj->setValueForKey($value, $key);
			if(method_exists($this, 'didChangeValueForKeyPath'))
				$this->didChangeValueForKeyPath($key);
		}
	}
	
/**
*	@inheritDoc
*/
	public function setValueForUndefinedKey($value, $key) {
		// The default implementation does not allow to access undefined keys.
		// But you can override this method to specify custom actions.
		throw new \TASoft\KVC\Exception("Object `".get_class($this). "` is not key-value compliant for key `$key`", 192);
	}
	
	// PRIVATE METHODS
	
	private function _checkKey($key) {
		// Does not allow empty keys
		if(empty($key))
			throw new \TASoft\KVC\Exception("Key-Value coding does not allow empty keys.", 190);
		
		// checks, if the key is enabled, means the object has setter and getter for that key
		return in_array($key, $this->getDefinedKeys());
	}
	
	public function __get($key) {
		return $this->valueForKey($key);
	}

	public function __set($key, $value) {
		$this->setValueForKey($value, $key);
	}
	
/**
*	Implementation of ArrayAccess
*/
	public function offsetExists($key) {
		return in_array(explode(".", $key,2)[0], $this->definedKeys());
	}
		
	public function offsetGet($key) {
		return $this->valueForKeyPath($key);
	}
	public function offsetSet($key, $value) {
		$this->setValueForKeyPath($value, $key);
	}
	public function offsetUnset($key) {
		$this->unsetValueForKey($key);
	}
}