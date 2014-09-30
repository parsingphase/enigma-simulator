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

    use RotaryAlphaNumericTrait;

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
        $rotorInputCharacter = $this->incrementCharacterByOffset(
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

        $outputCharacter = $this->incrementCharacterByOffset(
            $rotorOutputCharacter,
            0 - ($this->rotorOffset - 1)
        );
        return $outputCharacter;
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
