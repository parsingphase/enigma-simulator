<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 01/10/14
 * Time: 17:45
 */

namespace Phase\Enigma;

class RotaryAlphaNumericTraitTest extends \PHPUnit_Framework_TestCase
{
    use RotaryAlphaNumericTrait;

    public function testIncrementCharacterByOffset()
    {
        $this->assertSame('B', $this->incrementCharacterByOffset('A', 1));
        $this->assertSame('A', $this->incrementCharacterByOffset('Y', 2));
    }

    /**
     * @param string $char
     * @param int $offset
     * @dataProvider badCharOffsetProvider
     * @expectedException \InvalidArgumentException
     */
    public function testIncrementCharacterByOffsetBadInput($char, $offset)
    {
        $this->incrementCharacterByOffset($char, $offset);
    }

    public function badCharOffsetProvider()
    {
        return [
            ['A', 'A'],
            [13, 2],
            [null, 5]
        ];
    }


    public function testCharToAlphabetPosition()
    {
        $this->assertSame(1, $this->charToAlphabetPosition('A'));
    }

    /**
     * @param string $character
     * @dataProvider badCharacterProvider
     * @expectedException \InvalidArgumentException
     */
    public function testCharToAlphabetPositionBadInput($character)
    {
        $this->charToAlphabetPosition($character);
    }

    public function badCharacterProvider()
    {
        return [
            [0],
            [null],
            [''],
        ];
    }

    public function testAlphabetPositionToCharacter()
    {
        $this->assertSame('E', $this->alphabetPositionToCharacter(5));
    }

    /**
     * @param int $position
     * @dataProvider badPositionProvider
     * @expectedException \InvalidArgumentException
     */
    public function testAlphabetPositionToCharacterBadInput($position)
    {
        $this->alphabetPositionToCharacter($position);
    }

    public function badPositionProvider()
    {
        return [
            [0],
            [-1],
            [27],
            [null],
            ['B'],
        ];
    }
}
