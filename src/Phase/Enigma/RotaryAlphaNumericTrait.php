<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 30/09/14
 * Time: 16:08
 */

namespace Phase\Enigma;

/**
 * Common functions to do with interchange between alphabet position and character and rotary maths
 * @package Phase\Enigma
 */
trait RotaryAlphaNumericTrait
{

    protected function incrementCharacterByOffset($character, $offset)
    {
        $offset = $offset % 26; // -25..0..25

        if ($offset < 0) {
            $offset += 26; // 0..25
        }

        $charAsInt = $this->charToAlphabetPosition($character);

        $newInteger = ($charAsInt + $offset) % 26;

        if ($newInteger == 0) {
            $newInteger = 26;
        }

        $newCharacter =
            $this->alphabetPositionToCharacter($newInteger);

        return ($newCharacter);
    }

    protected function charToAlphabetPosition($char)
    {
        $char = strtoupper($char);
        if (!preg_match('/^[A-Z]$/', $char)) {
            throw new \InvalidArgumentException('Letters A-Z only accepted');
        }

        $position = (ord($char) - 64);

        return $position;
    }

    protected function alphabetPositionToCharacter($position)
    {
        if ($position < 1 || $position > 26) {
            throw new \InvalidArgumentException('Position must be between 1 and 26');
        }
        $char = (chr($position + 64));

        return $char;
    }
}
