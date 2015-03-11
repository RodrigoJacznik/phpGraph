<?php 

class Graph {
  private $nodes = Array();

  /**
   * Add a node to the graph.
   *
   * @param node.
   */
  public function add($node) {
    $this->nodes[$node->getName()] = $node;
  }

  /**
   * Delete a existing node.
   *
   */
  public function deleteNode($name) {
    $node = $this->searchByName($name);
    if ($node !== null) {
      foreach($this->nodes as $neighbor) {
        if ($neighbor->isNeighbor($node)) {
          $neighbor->deleteRelation($node->getName());
        }
      }  
    unset($this->nodes[$name]);
    }
  }

  /*
   * Returns all the nodes on the graph.
   *
   * @return node[] a array of nodes.
   */
  public function getNodes() {
    return $this->nodes;
  }

  /*
   * Search a node by the name.
   * If the node don't exist return null.
   *
   * @param name
   * @return Node or null
   */
  public function searchByName($node_name) {
    if (array_key_exists($node_name, $this->nodes)) {
      return $this->nodes[$node_name];
    } else {
      return null;
    }
  }

  /*
   * Connect *node1* with *node2*.
   * The connection is unidirectional by default.
   *
   * @parm node1
   * @parm node2
   * @parm bidirectional 
   */
  public function connectNodes($node1, $node2, $bidirectional=False) {
    $node1->connectTo($node2);
    if ($bidirectional) {
      $node2->connectTo($node1);
    }
  }

  /*
   * Test if *node1* and *node2* are adjacent.
   * Two nodes are adjacent if exists a relation between them.
   *
   * @return True if the two nodes have a connection, otherwise false.
   */
  public function areAdjacent($node1, $node2) {
    if (array_key_exists($node1->getName(), $this->nodes) and\
                          array_key_exists($node2->getName(), $this->nodes)) {
      return $node1->isNeighbor($node2) or $node2->isNeighbor($node1);
    }
    return False;
  }
 
  function indexOf($arr, $name) {
      for ($i = 0; $i < count($arr); $i++) {
        if ($arr[$i]['name'] == $name) {
          return $i;
        }
      }
      return -1;
    }

  /*
   * Convert the graph to json (d3.js like).
   *
   * @return String
   */
  public function toJson() {
   
    $json = array('nodes' => [], 'links' => []);

    // FEO pero funciona por ahora
    foreach($this->nodes as $node) {

      $index_source = $this->indexOf($json['nodes'], $node->getName());
      if($index_source === -1) {
        array_push($json['nodes'], $node->toDictionary());
        $index_source = count($json['nodes']) - 1;
      } 
      foreach($node->getNeighbors() as $neighbor) {
        $index_target = $this->indexOf($json['nodes'], $neighbor->getName());
        if ($index_target === -1) {
          array_push($json['nodes'], $neighbor->toDictionary());
          $index_target = count($json['nodes']) - 1;
        }
        array_push($json['links'], ['source' => $index_source, 'target' => $index_target]);
      }
    }

    return json_encode($json);
  }
}
