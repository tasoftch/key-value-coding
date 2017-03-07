<?php
/**
*	TASoft Key-Value-Coding
*	@link https://www.tasoft.ch
*	@copyright 2017 - 2027 (c) TASoft Applications, Th. Abplanalp
*	@license MIT
*/
namespace TASoft\KVC;

/**
*	The KVC Helper Class is designed to resolve keypaths and trace to objects for you.
*	Tracing to objects which do not implement the Object\CodingInterface will cause an exception.
*/
class KeyPathHelperClass {
	
/**
*	Reads a keyPath and extract the final key and the path to that object.
*	For example a keypath of "customer.address.country" will be resolved to an array of
*	["customer", "address"] and a key named "country".
*	@param keyPath string
*	@param key string* The final key
*	@return array
*/
	public static function parseKeyPath($keyPath, &$key=NULL) {
		$kp = explode(".", $keyPath);
		$key = array_pop($kp);
		return $kp;
	}
	
/**
*	Traces from a given object by applying a keypath to the target object, extracting the final key as well.
*	It does not return the key value itself. Only the last object in the chain.
*	@param keyPath string The keypath
*	@param object object An object to trace
*	@param key string* the final key
*	@return object
*/
	public static function traceKeyPathWithObject(
		$keyPath,
		$object,
		&$key = NULL
	) {
		foreach(self::parseKeyPath($keyPath, $key) as $path) {
			if(!method_exists($object, 'valueForKey'))
				throw new Exception("Object `".get_class($object). "` is not key-value compliant", 193);
			
			$object = $object->valueForKey($path);
			
			if(is_null($object))
				return NULL;
			
			if(!is_object($object))
				throw new Exception("Key-Value coding does only accept object values. Key `$path` is `$object`", 194);
		}
		return $object;
	}
}
