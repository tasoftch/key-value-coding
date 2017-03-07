# Key Value Coding
PHP Library to implement key-value coding
This library defined 3 interfaces to enable key-value coding, key-value changing and key-value observing. Key-value coding is known as accessing properties, such as attributes or relationships of an object via keys.
The key-value-coding library ships with 2 traits that implement the coding and changing interfaces.

# Usage
Every object that has methods named get* and set* can be used for key-value coding. The Object\CodingTrait implements the default mechanism which has the following workflow:<br>
Getting a key:
```
echo $object->userName;
```
will do:
```
$object->__get("userName");
$object->valueForKey("userName");
$object->getDefinedKeys();
// If key does exist
$object->getUserName();
// If key does not exist
$object->valueForUndefinedKey("userName");
```
and the same for
```
$object->userName = "tasoft";
```
but using the \__set, setValueForKey, setUserName or setValueForUndefinedKey instead.
