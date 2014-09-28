<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 26/09/14
 * Time: 09:39
 */

namespace Phase\Enigma;

class RotorSlot implements EncryptorInterface
{

    /**
     * @var Rotor
     */
    protected $rotor;
    /**
     * @var int
     */
    protected $rotorOffset;

    /**
     * @var Pawl
     */
    protected $leftPawl;

    /**
     * @var Pawl
     */
    protected $rightPawl;

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
        return $this->encipherCharacterDirectional($inputCharacter, true);
    }

    public function getOutputCharacterForInputCharacterReversedSignal($inputCharacter)
    {
        return $this->encipherCharacterDirectional($inputCharacter, false);
    }


    /**
     * @param $inputCharacter
     * @return string
     */
    protected function encipherCharacterDirectional($inputCharacter, $forward)
    {
        $inputCharacter = strtoupper($inputCharacter);
        $rotorInputCharacter = $this->getCharacterOffsetBy(
            $inputCharacter,
            $this->rotorOffset - 1
        );

        if ($forward) {
            $rotorOutputCharacter = $this->rotor->getOutputCharacterForInputCharacter(
                $rotorInputCharacter
            );
        } else {
            $rotorOutputCharacter = $this->rotor->getOutputCharacterForInputCharacterReversedSignal(
                $rotorInputCharacter
            );
        }

        $outputCharacter = $this->getCharacterOffsetBy(
            $rotorOutputCharacter,
            0 - ($this->rotorOffset - 1)
        );
        return $outputCharacter;
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

    /**
     * @return Pawl
     */
    public function getLeftPawl()
    {
        return $this->leftPawl;
    }

    /**
     * @param Pawl $leftPawl
     */
    public function setLeftPawl($leftPawl)
    {
        $this->leftPawl = $leftPawl;
    }

    /**
     * @return Pawl
     */
    public function getRightPawl()
    {
        return $this->rightPawl;
    }

    /**
     * @param Pawl $rightPawl
     */
    public function setRightPawl($rightPawl)
    {
        $this->rightPawl = $rightPawl;
    }

    /**
     * Are either of the pawls adjacent to this slot in a position to engage the rotor?
     */
    public function canEngagePawl()
    {
        return (($this->leftPawl && $this->leftPawl->canPush()) || ($this->rightPawl && $this->rightPawl->canPush()));
    }
}
