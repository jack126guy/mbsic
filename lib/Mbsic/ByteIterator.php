<?php
/**
 * This file is part of Mbsic. Please refer to LICENSE.txt for
 * license information.
 * @copyright 2016 Jack126Guy
 * @license MIT
 */

/**
 * Iterator that provides bytes from a data source
 *
 * The data source may be a string, an array of strings, or a Traversable
 * object that yields strings.
 */
class Mbsic_ByteIterator implements Iterator {
	/**
	 * @var Iterator Data source transformed into an interator
	 */
	protected $source;

	/**
	 * @var string Current string
	 */
	protected $curStr;

	/**
	 * @var int Length of current string
	 */
	protected $curLen;

	/**
	 * @var int Current position in string
	 */
	protected $curPos;

	/**
	 * @var int Byte key (current byte index in this iterator)
	 */
	protected $key;

	/**
	 * Create a new byte iterator using the given data source.
	 * @param string|string[]|Traversable $source Data source
	 */
	public function __construct($source) {
		if(is_string($source)) {
			$this->source = new ArrayIterator(array($source));
		} elseif(is_array($source)) {
			$this->source = new ArrayIterator($source);
		} elseif(is_object($source) AND $source instanceof Traversable) {
			$this->source = new IteratorIterator($source);
		} else {
			throw new InvalidArgumentException('Invalid data source');
		}

		$this->key = 0;
		$this->initString();
	}

	/**
	 * Get the current string from the source and reset the $cur* properties.
	 * If there is no current string, the properties are set to invalid values.
	 */
	protected function initString() {
		if($this->source->valid()) {
			$this->curStr = (string) $this->source->current();
			$this->curLen = strlen($this->curStr);
			$this->curPos = 0;
		} else {
			$this->curStr = '';
			$this->curLen = 0;
			$this->curPos = 0;
		}
	}

	/**
	 * Get the next byte.
	 * @return string|null Next byte, or `NULL` if there are no more bytes
	 */
	public function current() {
		if($this->curPos < $this->curLen) {
			return $this->curStr[$this->curPos];
		} else {
			//No more data
			return NULL;
		}
	}

	/**
	 * Get the key of the current byte, which is the index of this byte
	 * in this iterator.
	 * @return int Byte index
	 */
	public function key() {
		return $this->key;
	}

	/**
	 * Advance to the next byte.
	 */
	public function next() {
		$this->curPos++;
		if($this->curPos >= $this->curLen) {
			$this->source->next();
			$this->initString();
		}
		$this->key++;
	}

	/**
	 * Rewind the iterator.
	 * This calls `rewind()` on the source, which may fail.
	 */
	public function rewind() {
		$this->source->rewind();
		$this->key = 0;
		$this->initString();
	}

	/**
	 * Check if the current position is valid.
	 * @return bool Whether or not the current position is valid
	 */
	public function valid() {
		return $this->curPos < $this->curLen;
	}
}