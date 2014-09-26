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
            'A'=>'E','B'=>'K','C'=>'M','D'=>'F','E'=>'L','F'=>'G','G'=>'D',
            'H'=>'Q','I'=>'V','J'=>'Z','K'=>'N','L'=>'T','M'=>'O','N'=>'W',
            'O'=>'Y','P'=>'H','Q'=>'X','R'=>'U','S'=>'S','T'=>'P','U'=>'A',
            'V'=>'I','W'=>'B','X'=>'R','Y'=>'C','Z'=>'J'
        ];

        $rotor->setRingOffset($ringOffset);
        $rotor->setCoreMapping($coreMapping);

        $slot = new RotorSlot();
        $slot->loadRotor($rotor);
        $slot->setRotorOffset($rotorOffset);

        $slotOutput = $slot->getOutputCharacterForInputCharacter($slotInput);

        $this->assertSame($output, $slotOutput);
    }

    public function singleSlotRotorOneRingOffsetOneDataProvider()
    {
        return[
            //$slotInput, $rotorOffset, $ringOffset, $output
            ['V','A','Z','A'],
            ['V','C','Z','Z'],
        ];
    }


}
