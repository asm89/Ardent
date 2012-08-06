<?php

namespace Spl;

class PostOrderIterator implements BinaryTreeIterator {

    /**
     * @var ArrayStack
     */
    protected $stack;

    /**
     * @var BinaryNode
     */
    protected $root;

    /**
     * @var BinaryNode
     */
    protected $value;

    /**
     * @var BinaryNode
     */
    protected $current;

    public function __construct(BinaryNode $root = NULL) {
        $this->stack = new ArrayStack;
        $this->root = $root;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    public function current() {
        return $this->current->getValue();
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    public function next() {
        /**
         * @var BinaryNode $node
         */
        if ($this->value !== NULL) {
            $right = $this->value->getRight();
            if ($right !== NULL) {
                $this->stack->push($right);
            }
            $this->stack->push($this->value);
            $this->value = $this->value->getLeft();
            $this->next();
            return;
        }

        if ($this->stack->isEmpty()) {
            $this->current = $this->value;
            $this->value = NULL;
            return;
        }

        $this->value = $this->stack->pop();

        $right = $this->value->getRight();
        if ($right !== NULL && !$this->stack->isEmpty() && $right === $this->stack->peek()) {
            $this->stack->pop();
            $this->stack->push($this->value);
            $this->value = $right;
            $this->next();
        } else {
            $this->current = $this->value;
            $this->value = NULL;
        }

    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return NULL
     */
    public function key() {
        return NULL; //no keys in a tree . . .
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    public function valid() {
        return $this->current !== NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    public function rewind() {
        $this->stack->clear();

        $this->value = $this->root;
        $this->next();
    }
}
