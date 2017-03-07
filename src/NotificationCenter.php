<?php
namespace TASoft\KVC;

use TASoft\KVC\Observer\ObserverInterface;

final class NotificationCenter { 
	private static $observers = [];
	
	public static function addObserver(ObserverInterface $observer, $keyPaths, $object = NULL) {
		self::$observers[] = [
			'type' => 'observer',
			'object' => $observer,
			'keyPaths' => !is_array($keyPaths) ? [$keyPaths] : $keyPaths,
			'listen' => $object
		];
	}
	
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