<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 27/09/14
 * Time: 10:46
 */

namespace Phase\Enigma;


class PawlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param RotorSlot $rightSlot
     * @dataProvider primedSlotProvider
     */
    public function testCanPushOnValidConditions(RotorSlot $rightSlot)
    {
        $pawl = new Pawl;
        $pawl->setRightRotorSlot($rightSlot);
        $this->assertTrue($pawl->canPush());
    }


    /**
     * @param RotorSlot $rightSlot
     * @dataProvider primedSlotProvider We can use the existing provided and un-prime the rotors
     */
    public function testCannotPushOnInvalidConditions(RotorSlot $rightSlot)
    {
        $rightSlot->incrementRotorOffset(); // No provided rotor has two adjacent notches
        $pawl = new Pawl;
        $pawl->setRightRotorSlot($rightSlot);
        $this->assertFalse($pawl->canPush());
    }


    public function primedSlotProvider()
    {
        $rotorFactory = new RotorFactory();
        // use only rotors with notches
        $rotors = [
            $rotorFactory->buildRotorInstance(RotorFactory::ROTOR_ONE),
            $rotorFactory->buildRotorInstance(RotorFactory::ROTOR_THREE),
            $rotorFactory->buildRotorInstance(RotorFactory::ROTOR_SIX),
        ];

        $params = [];

        foreach ($rotors as $rotor) {
            /* @var Rotor $rotor */
            $rotorNotchPositions = $rotor->getNotchPositions();
            $slot = new RotorSlot();
            $slot->loadRotor($rotor);
            $slot->setRotorOffset($rotorNotchPositions[0]); // loaded rotor is at a pushable position
            $params[] = [$slot];
        }

        return $params;
    }
}
