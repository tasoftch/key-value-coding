<?php
namespace TASoft\KVC;

class KeyPathHelperClass {
	
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
	
	public static function parseKeyPath($keyPath, &$key=NULL) {
		$kp = explode(".", $keyPath);
		$key = array_pop($kp);
		return $kp;
	}
}