<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 28/09/14
 * Time: 12:12
 */

namespace Phase\Enigma;

/**
 * Structural representation of a highly configurable Enigma machine
 * @package Phase\Enigma
 */
class Machine implements EncryptorInterface
{

    /**
     * Array of RotorSlot objects, ordered 0..N, right-to-left, representing number of slots in this particular machine
     * @var RotorSlot[]
     */
    protected $rotorSlots;

    /**
     * Array of Pawl objects, ordered 0..N, right-to-left, representing position of pawls in this particular machine.
     * $pawls[n] lies to the right of $rotorSlots[n] and to the left of $rotorSlots[n-1]
     *
     * @var Pawl[]
     */
    protected $pawls;

    /**
     * Plugboard to be used. Currently required; leave unconfigured to simulate absence
     * @todo Make this optional
     *
     * @var Plugboard
     */
    protected $plugboard;

    /**
     * Reflector to use, required
     * @var Reflector
     */
    protected $reflector;

    /**
     * Get pawl positions, @see Machine::pawls
     * @return Pawl[]
     */
    public function getPawls()
    {
        return $this->pawls;
    }

    /**
     * Set pawl positions, @see Machine::pawls for structure
     * @param Pawl[] $pawls
     */
    public function setPawls($pawls)
    {
        $this->pawls = $pawls;
        $this->setupMechanicalInterconnects();
    }

    /**
     * Get plugboard object
     * @return Plugboard
     */
    public function getPlugboard()
    {
        return $this->plugboard;
    }

    /**
     * Set plugboard, @see Machine::plugboard
     * @param Plugboard $plugboard
     */
    public function setPlugboard(Plugboard $plugboard)
    {
        $this->plugboard = $plugboard;
    }

    /**
     * Get rotor slots array
     * @return RotorSlot[]
     */
    public function getRotorSlots()
    {
        return $this->rotorSlots;
    }

    /**
     * Set rotor slots, @see Machine::rotorSlots for structure
     * @param RotorSlot[] $rotorSlots
     */
    public function setRotorSlots($rotorSlots)
    {
        $this->rotorSlots = $rotorSlots;
        $this->setupMechanicalInterconnects();
    }

    /**
     * @return Reflector
     */
    public function getReflector()
    {
        return $this->reflector;
    }

    /**
     * Set a configured Reflector
     * @param Reflector $reflector
     */
    public function setReflector(Reflector $reflector)
    {
        $this->reflector = $reflector;
    }

    /**
     * Reset the mechanical interconnections between pawls and rotors for as many of each as are currently configured
     */
    public function setupMechanicalInterconnects()
    {
        // interconnect pawls and rotor slots
        foreach ($this->rotorSlots as $index => $rotorSlot) {
            if (isset($this->pawls[$index])) {
                $rightPawl = $this->pawls[$index];
                $rotorSlot->setRightPawl($rightPawl);
            }

            if (isset($this->pawls[$index + 1])) {
                $leftPawl = $this->pawls[$index + 1];
                $rotorSlot->setLeftPawl($leftPawl);
                $leftPawl->setRightRotorSlot($rotorSlot);
            }
        }
    }

    /**
     * Handle the rotor turnover effects of a mechanical keypress
     */
    public function performTurnover()
    {
        $rotorSlotsToTurn = [];
        foreach ($this->rotorSlots as $index => $rotorSlot) {
            if ($rotorSlot->canEngagePawl()) {
                $rotorSlotsToTurn[] = $index;
            }
        }
        foreach ($rotorSlotsToTurn as $index) {
            $this->rotorSlots[$index]->incrementRotorOffset();
        }
    }

    /**
     * Return the output for the given encryptor input in its current state
     *
     * @param string $inputCharacter Single character, uppercase
     * @return string Single character, uppercase
     */
    public function getOutputCharacterForInputCharacter($inputCharacter)
    {
        //Mechanical phase happens first
        $this->performTurnover();

        $signal = $inputCharacter;

        // Route through plugboard
        $signal = $this->plugboard->getOutputCharacterForInputCharacter($signal);

        // Route through each rotor slot in turn, right to left
        foreach ($this->rotorSlots as $rotorSlot) {
            $signal = $rotorSlot->getOutputCharacterForInputCharacter($signal);
        }

        // Route through reflector
        $signal = $this->reflector->getOutputCharacterForInputCharacter($signal);

        // Now route through rotors, in reverse order, right to left
        $rotorSlotsBackwards = array_reverse($this->rotorSlots);
        /* @var RotorSlot[] $rotorSlotsBackwards */
        foreach ($rotorSlotsBackwards as $rotorSlot) {
            $signal = $rotorSlot->getOutputCharacterForInputCharacterReversedSignal($signal);
        }

        // Route back out through the plugboard (which is symmetrical)
        $signal = $this->plugboard->getOutputCharacterForInputCharacter($signal);

        return $signal;
    }
}
