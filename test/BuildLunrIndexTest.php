<?php

use LunrPHP\BuildLunrIndex;

class BuildLunrIndexTest extends \PHPUnit\Framework\TestCase {

  public function setUp(): void {
		$this->instance = new BuildLunrIndex();

		$this->instance->ref('identifier');
		$this->instance->field("title");
		$this->instance->field("description");
		$this->instance->field("theme");
		$this->instance->addPipeline('LunrPHP\LunrDefaultPipelines::trimmer');
		$this->instance->addPipeline('LunrPHP\LunrDefaultPipelines::stop_word_filter');
		$this->instance->addPipeline('LunrPHP\LunrDefaultPipelines::stemmer');

		$string = file_get_contents("__DIR__ /../fixtures/fixture.json");
		$datasets = json_decode($string, true);

		foreach ($datasets as $dataset) {
			$this->instance->add($dataset);
		}

		$this->output = $this->instance->output();
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

	function testOutput() {
		$this->assertEquals($this->output->pipeline[0], 'stemmer');

		$this->assertEquals($this->output->fields[0], 'title');
		$this->assertEquals($this->output->fields[1], 'description');

		$this->assertEquals($this->output->fieldVectors[0][0], 'title/id1');
		$this->assertEquals($this->output->fieldVectors[0][1][0], 0);
		$this->assertEquals($this->output->fieldVectors[0][1][1], "0.105");
		$this->assertEquals($this->output->fieldVectors[5][0], 'theme/id2');
		$this->assertEquals($this->output->fieldVectors[5][1][0], 1);
		$this->assertEquals($this->output->fieldVectors[5][1][1], "0.241");

		$this->assertEquals($this->output->invertedIndex[5][0], 'boop');
		$this->assertEquals($this->output->invertedIndex[5][1]->{"_index"}, 19);

  }


}
