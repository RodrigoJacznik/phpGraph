<?php

define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/src/graph.php');

class GraphTest extends PHPUnit_Framework_TestCase {
  
  public function setUp() {
    $this->graph = new Graph();
  }

  public function testCanCreateAGraph() {
    $this->assertInstanceOf('Graph', $this->graph);
  }

  public function testCanAddANode() {
    $node = new Node('node');

    $this->graph->add($node);
    $this->assertContains($node, $this->graph->getNodes());
  }

  public function testCanNotAddTheSameNodeTwice() {
    $node1 = new Node('node1');
    $node2 = new Node('node1');

    $this->graph->add($node1);
    $this->graph->add($node2);

    $this->assertEquals(1, count($this->graph->getNodes()));
  }

  public function testCanDeleteAExistingNode() {
    $node1 = new Node('node1');
    $node2 = new Node('node2');
    $node3 = new Node('node3');

    $this->graph->add($node1);
    $this->graph->add($node2);
    $this->graph->add($node3);
    $this->graph->connectNodes($node1, $node2, $bidirectional=True);
    $this->graph->connectNodes($node3, $node1);
    $this->graph->connectNodes($node3, $node2);

    $this->graph->deleteNode('node1');
    $this->assertFalse($this->graph->areAdjacent($node1, $node2));
    $this->assertFalse($this->graph->areAdjacent($node2, $node1));
    $this->assertEquals(2, count($this->graph->getNodes()));
  }

  public function testCanSearchANodeByName() {
    $node = new Node('node_name');
    $this->graph->add($node);

    $this->assertEquals($node, $this->graph->searchByName($node->getName()));
  }

  public function testSearchANotExistingNodeShuldReturnNull() {
    $this->assertNull($this->graph->searchByName('Not existing node'));
  }

  public function testCanConnectTwoNodes() {
    $node1 = new Node('node1');
    $node2 = new Node('node2');

    $this->graph->add($node1);
    $this->graph->add($node2);
    $this->graph->connectNodes($node1, $node2);
    $this->assertTrue($this->graph->areAdjacent($node1, $node2));
  }

  public function testCanConvertToJson() {
    $node1 = new Node('node1');
    $node2 = new Node('node2');
    $node3 = new Node('node3');
    $node4 = new Node('node4');

    $this->graph->add($node1);
    $this->graph->add($node2);
    $this->graph->add($node3);
    $this->graph->add($node4);

    $this->graph->connectNodes($node1, $node2);
    $this->graph->connectNodes($node2, $node3);
    $this->graph->connectNodes($node3, $node2);
    $this->graph->connectNodes($node4, $node2);
    $this->graph->connectNodes($node4, $node1);

    $this->assertJsonStringEqualsJsonString(
      '{"nodes":
          [
           {"name":"node1"},
           {"name":"node2"},
           {"name":"node3"},
           {"name":"node4"}
          ],
        "links":
          [
           {"source":0,"target":1},
           {"source":1,"target":2},
           {"source":2,"target":1},
           {"source":3,"target":1},
           {"source":3,"target":0}
          ]
       }',
      $this->graph->toJson());
  }
}
