x<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/src/graph.php');
require_once(__ROOT__.'/src/node.php');

$graph = new Graph();

$node1 = new Node('node1');
$node2 = new Node('node2');
$node3 = new Node('node3');

$graph->add($node1);
$graph->add($node2);
$graph->add($node3);
$graph->connectNodes($node1, $node2, $bidirectional=True);
$graph->connectNodes($node3, $node1);
$graph->connectNodes($node3, $node2);

echo $graph->toJson();

$graph->deleteNode('node1');

echo "\n";
echo $graph->toJson();
