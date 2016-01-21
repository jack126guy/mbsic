<?php
/**
 * This file is part of Mbsic. Please refer to LICENSE.txt for
 * license information.
 * @copyright 2016 Jack126Guy
 * @license MIT
 */

/**
 * Test class for Mbsic_ByteIterator
 * @see Mbsic_ByteIterator
 */
class Mbsic_ByteIteratorTest extends PHPUnit_Framework_TestCase {
	public function provideData() {
		return array(
			'empty string' => array('', array()),
			'printable ASCII string' => array('abc', array('a', 'b', 'c')),
			'ASCII control string' => array("\0\n", array("\0", "\n")),
			'empty array' => array(array(), array()),
			'array with one string' => array(array('ab'), array('a', 'b')),
			'array with multiple strings' =>
				array(array('ab', 'cd'), array('a', 'b', 'c', 'd'))
			,
			'ArrayIterator' =>
				array(new ArrayIterator(array('a', 'b')), array('a', 'b'))
			,
		);
	}

	/**
	 * @dataProvider provideData
	 */
	public function testData($input, $result) {
		$iterator = new Mbsic_ByteIterator($input);
		$actualResult = array();
		foreach($iterator as $byte) {
			array_push($actualResult, $byte);
		}
		$this->assertSame($result, $actualResult);
	}
}