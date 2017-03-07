<?php
/**
*	TASoft Key-Value-Coding
*	@link https://www.tasoft.ch
*	@copyright 2017 - 2027 (c) TASoft Applications, Th. Abplanalp
*	@license MIT
*/
namespace TASoft\KVC;
use TASoft\KVC\Observer\ObserverInterface;

/**
*	The NotificationCenter is designed to interact with changings of object.
*	You can register observers which can interact with the changing system of key-value coding.
*/
final class NotificationCenter { 
	private static $observers = [];
	
/**
*	Registers an observer to one or more keypaths. If any such keypath changes, the registered
*	observer will be notified. By passing an object as 3rd argument, your observer only will be
*	notified if the registered keypaths of that object changed.
*	@param observer ObserverInterface
*	@param keyPath array|string One or more keypaths
*	@param object object Object\ChangingInterface
*/
	public static function addObserver(ObserverInterface $observer, $keyPaths, $object = NULL) {
		self::$observers[] = [
			'type' => 'observer',
			'object' => $observer,
			'keyPaths' => !is_array($keyPaths) ? [$keyPaths] : $keyPaths,
			'listen' => $object
		];
	}
	
/**
*	Intern use to notify the notification center that there happend some changes
*	@param keyPath string The keypath that changed
*	@param object Object\ChangingInterface The affected object
*	@param changes array Associative array containing the changes
*/
	public static function postNotification($keyPath, $object, $changes) {
		foreach(self::$observers as $obs) {
			if(in_array($keyPath, $obs['keyPaths'])) {
				
				if($obs['listen'] && $obs['listen'] != $object)
					continue;
				
				if($obs['type'] == 'observer') {
					$observer = $obs['object'];
					$observer->observedObjectDidChange($object, $keyPath, $changes);
				}
			}
		}
	}
}
