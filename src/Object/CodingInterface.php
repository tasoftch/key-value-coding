<?php
/**
*	TASoft Key-Value-Coding
*	@link https://www.tasoft.ch
*	@copyright 2017 - 2027 (c) TASoft Applications, Th. Abplanalp
*	@license MIT
*/
namespace TASoft\KVC\Object;

/**
*	Implementing this interface enables your object to use key-value coding.
*	Keys are attributes of your object, where keypaths are relationships of
*	your object to other objects and their attributes.
*	So a key "userName" should be equivalent to $obj->userName.
*	A keypath of "customer.address.zip" for example of an order object will
*	access: $this->customer->address->zip via the key-value coding mechanism.
*/
interface CodingInterface {
/**
*	Provide value for key
*	@param aKey string
*	@return mixed
*/
	public function valueForKey($aKey);
	
/**
*	Provide a value for keypath
*	@param keyPath string
*	@return mixed
*/
	public function valueForKeyPath($keyPath);
	
/**
*	Provide a default value, if an undefined key is requested
*	@param key string
*	@return mixed
*/
	public function valueForUndefinedKey($key);
	
/**
*	Set value for key
*	@param aKey string
*	@param value mixed
*/
	public function setValueForKey($value, $aKey);
	
/**
*	Set value for a keypath
*	@param keyPath string
*	@param value mixed
*/
	public function setValueForKeyPath($value, $keyPath);
	
/**
*	Set value for an undefined key
*	@param key string
*	@param value mixed
*/
	public function setValueForUndefinedKey($value, $key);
}
