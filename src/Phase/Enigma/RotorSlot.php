<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 26/09/14
 * Time: 09:39
 */

namespace Phase\Enigma;

use Instantiator\Exception\ExceptionInterface;

class RotorSlot implements ExceptionInterface
{

    /**
     * @var Rotor
     */
    protected $rotor;
    /**
     * @var int
     */
    protected $rotorOffset;


    public function loadRotor(Rotor $rotor)
    {
        $this->rotor = $rotor;
    }

    public function setRotorOffset($offset)
    {
        if (preg_match('/^[A-Z]$/', $offset)) {
            $offset = $this->charToAlphabetPosition($offset);
        } else {
            if (!is_integer($offset) || ($offset < 1) || ($offset > 26)) {
                throw new \InvalidArgumentException("Offset must be integer in range 1..26");
            }
        }

        $this->rotorOffset = $offset;
    }

    public function getOutputCharacterForInputCharacter($inputCharacter)
    {
        $inputCharacter = strtoupper($inputCharacter);
        $rotorInputCharacter = $this->getCharacterOffsetBy(
            $inputCharacter,
            $this->rotorOffset - 1
        );
        $rotorOutputCharacter = $this->rotor->getOutputCharacterForInputCharacter(
            $rotorInputCharacter
        );
        $outputCharacter = $this->getCharacterOffsetBy(
            $rotorOutputCharacter,
            0 - ($this->rotorOffset - 1)
        );

        return ($outputCharacter);
    }

    protected function getCharacterOffsetBy($character, $offset)
    {
        $charAsInt =
            $this->charToAlphabetPosition($character);

        $newInteger = (26 + $charAsInt + $offset) % 26; // IMPORTANT! offsets work the other way from rings!

        if ($newInteger == 0) {
            $newInteger = 26;
        }

        $newCharacter =
            $this->alphabetPositionToCharacter($newInteger);

        return ($newCharacter);
    }

    protected function charToAlphabetPosition($char)
    {
        $position = (ord($char) - 64);

        return $position;
    }

    protected function alphabetPositionToCharacter($position)
    {
        $char = (chr($position + 64));

        return $char;
    }

    /**
     * @return int
     */
    public function getRotorOffset()
    {
        return $this->rotorOffset;
    }

    public function incrementRotorOffset()
    {
        $this->rotorOffset++;
        if ($this->rotorOffset > 26) {
            $this->rotorOffset = 1;
        }
    }

    /**
     * @return Rotor
     */
    public function getRotor()
    {
        return $this->rotor;
    }
}
