<?php
/**
*	TASoft Key-Value-Coding
*	@link https://www.tasoft.ch
*	@copyright 2017 - 2027 (c) TASoft Applications, Th. Abplanalp
*	@license MIT
*/
namespace TASoft\KVC\Observer;
use TASoft\KVC\Object\ChangingInterface;

/**
*	Implement ObserverInterface for an object you want to register with TASoft\KVC\NotificationCenter
*/
interface ObserverInterface {
	public function observedObjectDidChange(ChangingInterface $object, $keyPath, $changes);
}
