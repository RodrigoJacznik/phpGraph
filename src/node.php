<?php

class Node {

  private $neighbors = Array();
  private $name;

  public function __construct($node_name) {
    $this->name = $node_name;
  }

  /*
   * Add a new neighbor to this node.
   * This relation is unidirectional.
   *
   * @param Node $node 
   */
  public function connectTo($node) {
    $this->neighbors[$node->getName()] = $node;
  }

  /**
   * Delete the relation of this node with the node with name *$name*.
   * 
   * @param $name of the node to delete.
   */
  public function deleteRelation($name) {
    if (array_key_exists($name, $this->neighbors)) {
      unset($this->neighbors[$name]);
    }
  }

  /*
   * Return all the neighbors of this node.
   *
   * @return Array of Node
   */
  public function getNeighbors() {
    return $this->neighbors;
  }

  /*
   * Determine if a *node* is Neighbor of this node.
   * A *node* is neighbor of this node if this node has a direct edge
   * with the *node*.
   *
   * @param Node node
   * @return True if the node is neighbor, false otherwise.
   */
  public function isNeighbor($node) {
    return array_key_exists($node->getName(), $this->neighbors);
  }

  /*
   * Convert the node to JSON.
   *
   * @return String. A well formed JSON.
   */
  public function toJson() {
    return json_encode($this->toDictionary());
  }

  /*
   * Convert the node attributes to Dictionary data structure.
   *
   * @return Array
   */
  public function toDictionary() {
    $properties = get_object_vars($this);
    unset($properties['neighbors']);

    return $properties;
  }

  public function getName() {
    return $this->name;
  }

}
