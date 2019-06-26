<?php

use LunrPHP\BuildLunrIndex;

class BuildLunrIndexTest extends \PHPUnit\Framework\TestCase {

  public function setUp(): void {
		$this->instance = new BuildLunrIndex();
	}



	/**
	 * Call protected/private method of a class.
	 *
	 * @param object &$object    Instantiated object that we will run method on.
	 * @param string $methodName Method name to call
	 * @param array  $parameters Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 */
	public function invokeMethod(&$object, $methodName, array $parameters = array()) {
		$reflection = new \ReflectionClass(get_class($object));
		$method = $reflection->getMethod($methodName);
		$method->setAccessible(true);

		return $method->invokeArgs($object, $parameters);
	}

  function testNewFieldEntry() {
    $term = 'red';
    $field = 'title';
    $fields = ['title', 'desc'];
    $termIndex = 4;
    $identifier = 'v1';
		$item = $this->invokeMethod($this->instance, 'newFieldEntry', array($term, $fields, $field, $termIndex, $identifier));
    $this->assertEquals($term, $item[0]);
    $this->assertEquals($item[1]->{"_index"}, $termIndex);

  }




}
