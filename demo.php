<?php

/**
 * @file
 * Demos for index builder.
 */

include('./vendor/autoload.php');

use LunrPHP\Pipeline;
use LunrPHP\LunrDefaultPipelines;
use LunrPHP\BuildLunrIndex;

// Shows pipeline.
$tokens = ["one", "two ", "the", "three", '$five', "description",];
$pipeline = new Pipeline();
$pipeline->add('LunrPHP\LunrDefaultPipelines::trimmer');
$pipeline->add('LunrPHP\LunrDefaultPipelines::stop_word_filter');
$pipeline->add('LunrPHP\LunrDefaultPipelines::stemmer');
$results = $pipeline->run($tokens);
var_dump($results);

// Shows building index.
$build = new BuildLunrIndex();
$build->ref('identifier');
$build->field("title");
$build->field("description");
$pipeline->add('LunrPHP\LunrDefaultPipelines::trimmer');
$pipeline->add('LunrPHP\LunrDefaultPipelines::stop_word_filter');
$pipeline->add('LunrPHP\LunrDefaultPipelines::stemmer');
$string = file_get_contents("./fixtures/fixture.json");
$datasets = json_decode($string, true);

foreach ($datasets as $dataset) {
  $build->add($dataset);
}

$output = $build->output();
echo json_encode($output, JSON_PRETTY_PRINT);
