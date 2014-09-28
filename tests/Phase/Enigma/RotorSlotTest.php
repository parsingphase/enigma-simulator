<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 26/09/14
 * Time: 09:22
 */

namespace Phase\Enigma;


class RotorSlotTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider singleSlotRotorOneRingOffsetOneDataProvider
     * @param $slotInput
     * @param $rotorOffset
     * @param $ringOffset
     * @param $output
     */
    public function testSingleSlotRotorOneRingOffsetOne($slotInput, $rotorOffset, $ringOffset, $output)
    {
        $rotor = new Rotor();
        $coreMapping = [ // Rotor I
            'A' => 'E',
            'B' => 'K',
            'C' => 'M',
            'D' => 'F',
            'E' => 'L',
            'F' => 'G',
            'G' => 'D',
            'H' => 'Q',
            'I' => 'V',
            'J' => 'Z',
            'K' => 'N',
            'L' => 'T',
            'M' => 'O',
            'N' => 'W',
            'O' => 'Y',
            'P' => 'H',
            'Q' => 'X',
            'R' => 'U',
            'S' => 'S',
            'T' => 'P',
            'U' => 'A',
            'V' => 'I',
            'W' => 'B',
            'X' => 'R',
            'Y' => 'C',
            'Z' => 'J'
        ];

        $rotor->setRingOffset($ringOffset);
        $rotor->setCoreMapping($coreMapping);

        $slot = new RotorSlot();
        $slot->loadRotor($rotor);
        $slot->setRotorOffset($rotorOffset);

        $slotOutput = $slot->getOutputCharacterForInputCharacter($slotInput);
        $this->assertSame($output, $slotOutput);

        $slotOutputReversed = $slot->getOutputCharacterForInputCharacterReversedSignal($slotOutput);
        $this->assertSame($slotOutputReversed, $slotInput);

    }

    public function singleSlotRotorOneRingOffsetOneDataProvider()
    {
        return [
            //$slotInput, $rotorOffset, $ringOffset, $output
            ['V', 'A', 'Z', 'A'],
            ['V', 'C', 'Z', 'Z'],
        ];
    }


    /**
     * @dataProvider offsetIncrementDataProvider
     */
    public function testRotorOffsetIncrement($originalOffset, $newOffset)
    {
        $rotor = new Rotor();
        $coreMapping = [
            'A' => 'E',
            'B' => 'K',
            'C' => 'M',
            'D' => 'F',
            'E' => 'L',
            'F' => 'G',
            'G' => 'D',
            'H' => 'Q',
            'I' => 'V',
            'J' => 'Z',
            'K' => 'N',
            'L' => 'T',
            'M' => 'O',
            'N' => 'W',
            'O' => 'Y',
            'P' => 'H',
            'Q' => 'X',
            'R' => 'U',
            'S' => 'S',
            'T' => 'P',
            'U' => 'A',
            'V' => 'I',
            'W' => 'B',
            'X' => 'R',
            'Y' => 'C',
            'Z' => 'J'
        ];

        $rotor->setRingOffset(1);
        $rotor->setCoreMapping($coreMapping);

        $slot = new RotorSlot();
        $slot->loadRotor($rotor);
        $slot->setRotorOffset($originalOffset);

        $this->assertSame($originalOffset, $slot->getRotorOffset());
        $slot->incrementRotorOffset();
        $this->assertSame($newOffset, $slot->getRotorOffset());
    }

    public function offsetIncrementDataProvider()
    {
        return [
            [1, 2],
            [13, 14],
            [26, 1]
        ];
    }

    /**
     * Test one pawl to the right of one rotor
     */
    public function testEngagePawlSingleRotorSinglePawl()
    {
        $pawl = new Pawl();
        $rotorFactory = new RotorFactory();
        $rotor = $rotorFactory->buildRotorInstance(RotorFactory::ROTOR_ONE);
        $rotorSlot = new RotorSlot();
        $rotorSlot->loadRotor($rotor);
        $rotorSlot->setRightPawl($pawl); // make pawls act on slots as it saves us reconfiguring if we swap rotors

        $this->assertTrue($pawl->canPush()); // no rotor to right of pawl
        $this->assertTrue($rotorSlot->canEngagePawl()); // will always push in this situation
    }

    /**
     * Test one pawl between two rotors
     */
    public function testEngagePawlTwoRotorsSinglePawl()
    {
        $pawl = new Pawl();
        $rotorFactory = new RotorFactory();
        $leftRotor = $rotorFactory->buildRotorInstance(RotorFactory::ROTOR_ONE);
        $leftRotorSlot = new RotorSlot();
        $leftRotorSlot->loadRotor($leftRotor);
        $leftRotorSlot->setRightPawl($pawl); // make pawls act on slots as it saves us reconfiguring if we swap rotors

        $rightRotor = $rotorFactory->buildRotorInstance(RotorFactory::ROTOR_TWO);
        $rightRotorSlot = new RotorSlot();
        $rightRotorSlot->loadRotor($rightRotor);
        $rightRotorSlot->setLeftPawl($pawl);

        $pawl->setRightRotorSlot($rightRotorSlot);

        // Pawl can only engage if both rotors are in turnover positions:
        $leftRotorSlot->setRotorOffset($leftRotor->getNotchPositions()[0]);
        $rightRotorSlot->setRotorOffset($rightRotor->getNotchPositions()[0]);

        $this->assertTrue($pawl->canPush());
        $this->assertTrue($leftRotorSlot->canEngagePawl());
        $this->assertTrue($rightRotorSlot->canEngagePawl());

        // then turn rotors, and neither can push on next attempt

        $leftRotorSlot->incrementRotorOffset();
        $rightRotorSlot->incrementRotorOffset();

        $this->assertFalse($pawl->canPush());
        $this->assertFalse($leftRotorSlot->canEngagePawl());
        $this->assertFalse($rightRotorSlot->canEngagePawl());
    }


    /**
     * Test two rotors with pawl to right of each
     */
    public function testEngagePawlTwoRotorsTwoPawls()
    {
        $leftPawl = new Pawl();
        $rightPawl = new Pawl();
        $rotorFactory = new RotorFactory();
        $leftRotor = $rotorFactory->buildRotorInstance(RotorFactory::ROTOR_ONE);
        $leftRotorSlot = new RotorSlot();
        $leftRotorSlot->loadRotor($leftRotor);
        $leftRotorSlot->setRightPawl(
            $leftPawl
        ); // make pawls act on slots as it saves us reconfiguring if we swap rotors

        $rightRotor = $rotorFactory->buildRotorInstance(RotorFactory::ROTOR_TWO);
        $rightRotorSlot = new RotorSlot();
        $rightRotorSlot->loadRotor($rightRotor);
        $rightRotorSlot->setLeftPawl($leftPawl);
        $rightRotorSlot->setRightPawl($rightPawl);

        $leftPawl->setRightRotorSlot($rightRotorSlot);

        // Pawl can only engage if both rotors are in turnover positions:
        $leftRotorSlot->setRotorOffset($leftRotor->getNotchPositions()[0]);
        $rightRotorSlot->setRotorOffset($rightRotor->getNotchPositions()[0]);

        $this->assertTrue($leftPawl->canPush());
        $this->assertTrue($rightPawl->canPush());
        $this->assertTrue($leftRotorSlot->canEngagePawl());
        $this->assertTrue($rightRotorSlot->canEngagePawl());

        // then turn rotors, and only right pawl/slot can push on next attempt

        $leftRotorSlot->incrementRotorOffset();
        $rightRotorSlot->incrementRotorOffset();

        $this->assertFalse($leftPawl->canPush());
        $this->assertTrue($rightPawl->canPush());
        $this->assertFalse($leftRotorSlot->canEngagePawl());
        $this->assertTrue($rightRotorSlot->canEngagePawl());
    }
}
