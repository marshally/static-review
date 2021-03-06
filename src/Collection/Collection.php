<?php
/*
 * This file is part of StaticReview
 *
 * Copyright (c) 2014 Samuel Parkinson <@samparkinson_>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
 */

namespace StaticReview\Collection;

use \Iterator;
use \Countable;

abstract class Collection implements Iterator, Countable
{
    protected $collection;

    /**
     * Initializes a new instance of the Collection class.
     */
    public function __construct(array $items = null)
    {
        $this->collection = [];

        if ($items) {
            foreach ($items as $item) {
                $this->append($item);
            }
        }
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->collection);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->collection);
    }

    /**
     * @return string
     */
    public function key()
    {
        return key($this->collection);
    }

    /**
     * @return mixed
     */
    public function next()
    {
        return next($this->collection);
    }

    /**
     * @return Collection
     */
    public function rewind()
    {
        reset($this->collection);

        return $this;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return key($this->collection) !== null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s(%s)', get_class($this), $this->count());
    }

    /**
     * @return Collection
     */
    public function append($item)
    {
        $this->validate($item);

        $this->collection[] = $item;

        return $this;
    }

    /**
     * @return bool
     * @throws InvalidArgumentException
     */
    abstract public function validate($item);

    /**
     * @param callable $filter
     * @return Collection
     */
    abstract public function select(callable $filter);
}
