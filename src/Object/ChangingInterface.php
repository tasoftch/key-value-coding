<?php
/**
*	TASoft Key-Value-Coding
*	@link https://www.tasoft.ch
*	@copyright 2017 - 2027 (c) TASoft Applications, Th. Abplanalp
*	@license MIT
*/
namespace TASoft\KVC\Object;

/**
*	The ChangingInterface enables a notification system for your objects.
*	It should handle how to notify observers that keys or keypaths changed.
*/
interface ChangingInterface extends CodingInterface {
/**
*	Should be called by the object itself before changing the value
*	@param key string
*/
	public function willChangeValueForKey($key);

/**
*	Should be called by the object itself after changing the value
*	@param key string
*/
	public function didChangeValueForKey($key);
	
	
/**
*	Should be called by the object itself before changing the value of a keypath
*	@param key string
*/
	public function willChangeValueForKeyPath($keyPath);
	
/**
*	Should be called by the object itself after changing the value of a keypath
*	@param key string
*/
	public function didChangeValueForKeyPath($keyPath);
}
