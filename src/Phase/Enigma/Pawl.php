<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 27/09/14
 * Time: 10:56
 */

namespace Phase\Enigma;

/**
 * Simulates one of the set of mechanical levers that forms part of the turnover mechanism
 * @package Phase\Enigma
 */
class Pawl
{

    use RotaryAlphaNumericTrait;

    /**
     * @var RotorSlot|null
     */
    protected $rightRotorSlot;

    /**
     * @return null|RotorSlot
     */
    public function getRightRotorSlot()
    {
        return $this->rightRotorSlot;
    }

    /**
     * @param null|RotorSlot $rightRotorSlot
     */
    public function setRightRotorSlot(RotorSlot $rightRotorSlot)
    {
        $this->rightRotorSlot = $rightRotorSlot;
    }

    public function canPush()
    {
        if ($this->rightRotorSlot && $this->rightRotorSlot->getRotor()) {
            $rotor = $this->rightRotorSlot->getRotor();
            $notchChars = $rotor->getNotchPositions();
            $rotorOffset = $this->rightRotorSlot->getRotorOffset();
            $rotorOffsetChar = $this->alphabetPositionToCharacter($rotorOffset);
            $canPush = in_array($rotorOffsetChar, $notchChars);
        } else {
            $canPush = true;
        }

        return $canPush;
    }
}
