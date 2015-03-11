<?php

if (! defined('__ROOT__')) {
    define('__ROOT__', dirname(dirname(__FILE__)));
}
require_once(__ROOT__.'/src/node.php');

class NodeTest extends PHPUnit_Framework_TestCase {

  public function testCanCreateANode() {
    $this->assertInstanceOf('Node', new Node('node'));
  }

  public function testCanAddRelationWithOtherNode() {
    $node1 = new Node('node1');
    $node2 = new Node('node2');

    $node1->connectTo($node2);
    $this->assertContains($node2, $node1->getNeighbors());
  }

  public function testCanNotAddTheSameRelationWithTheSameNode() {
    $node1 = new Node('node1');
    $node2 = new Node('node2');

    $node1->connectTo($node2);
    $node1->connectTo($node2);

    $this->assertEquals(1, count($node1->getNeighbors()));
  }

  public function testDeleteExistingRelation() {
    $node1 = new Node('node1');
    $node2 = new Node('node2');

    $node1->connectTo($node2);
    $node1->deleteRelation($node2->getName());

    $this->assertEquals(0, count($node1->getNeighbors()));
  }

  public function testCanGetHisName() {
    $node = new Node('node_name');
    $this->assertEquals('node_name', $node->getName());
  }

  public function testCanConvertToDictionary() {
    $node = new Node('node');
    $this->assertEquals(['name' => 'node'], $node->toDictionary());
  }

  public function testCanConvertToJson() {
    $node = new Node('node');
    $this->assertJsonStringEqualsJsonString(
      json_encode(array('name' => 'node')), $node->toJson()
    );
  }
}
