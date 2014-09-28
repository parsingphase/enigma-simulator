<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 28/09/14
 * Time: 12:33
 */

namespace Phase\Enigma;


class MachineTest extends \PHPUnit_Framework_TestCase
{

    public function testWholeMachine()
    {
        $machine = new Machine();

        $rotorFactory = new RotorFactory();

        $rotors = [
            $rotorFactory->buildRotorInstance(RotorFactory::ROTOR_ONE),
            $rotorFactory->buildRotorInstance(RotorFactory::ROTOR_TWO),
            $rotorFactory->buildRotorInstance(RotorFactory::ROTOR_THREE),
        ];

        /* @var Rotor[] $rotors */
        $rotors[0]->setRingOffset('C');
        $rotors[1]->setRingOffset('A');
        $rotors[2]->setRingOffset('A');

        /* @var RotorSlot[] $rotorSlots */
        $rotorSlots = [
            new RotorSlot(),
            new RotorSlot(),
            new RotorSlot(),
        ];

        foreach ($rotorSlots as $k => $v) {
            $v->loadRotor($rotors[$k]);
        }

        $rotorSlots[0]->setRotorOffset('P');
        $rotorSlots[1]->setRotorOffset('A');
        $rotorSlots[2]->setRotorOffset('A');

        /* @var Pawl[] $pawls */
        $pawls = [
            new Pawl(),
            new Pawl(),
            new Pawl(),
        ];

        $reflectorFactory = new ReflectorFactory();
        $reflector = $reflectorFactory->buildReflectorInstance(ReflectorFactory::REFLECTOR_B);

        $machine->setRotorSlots($rotorSlots);
        $machine->setPawls($pawls);
        $machine->setPlugboard(new Plugboard()); // defaults
        $machine->setReflector($reflector);

        $messageString = strtoupper('ActionThisDay');
        $messageArray = preg_split('//', $messageString, -1, PREG_SPLIT_NO_EMPTY);

        $out = [];

        foreach ($messageArray as $inChar) {
            $out[] = $machine->getOutputCharacterForInputCharacter($inChar);
        }
        $enciphered = join('', $out);

        $this->assertSame('DWOUFBIGODOGS', $enciphered);
    }


    /**
     * Test with message from http://users.telenet.be/d.rijmenants/en/m4project.htm
     * Note: using B-THIN reflector
     */
    public function testM4Machine()
    {
        $machine = new Machine();

        $rotorFactory = new RotorFactory();

        $rotors = [
            $rotorFactory->buildRotorInstance(RotorFactory::ROTOR_ONE),
            $rotorFactory->buildRotorInstance(RotorFactory::ROTOR_FOUR),
            $rotorFactory->buildRotorInstance(RotorFactory::ROTOR_TWO),
            $rotorFactory->buildRotorInstance(RotorFactory::ROTOR_BETA),
        ];

        /* @var Rotor[] $rotors */
        $rotors[0]->setRingOffset('V');
        $rotors[1]->setRingOffset('A');
        $rotors[2]->setRingOffset('A');
        $rotors[3]->setRingOffset('A');

        /* @var RotorSlot[] $rotorSlots */
        $rotorSlots = [
            new RotorSlot(),
            new RotorSlot(),
            new RotorSlot(),
            new RotorSlot(),
        ];

        foreach ($rotorSlots as $k => $v) {
            $v->loadRotor($rotors[$k]);
        }

        $rotorSlots[0]->setRotorOffset('A');
        $rotorSlots[1]->setRotorOffset('N');
        $rotorSlots[2]->setRotorOffset('J');
        $rotorSlots[3]->setRotorOffset('V');

        /* @var Pawl[] $pawls */
        $pawls = [
            new Pawl(),
            new Pawl(),
            new Pawl(), // not sure if M4 has 3 or 4 pawls.
        ];

        $reflectorFactory = new ReflectorFactory();
        $reflector = $reflectorFactory->buildReflectorInstance(ReflectorFactory::REFLECTOR_B_THIN);

        $plugboard = new Plugboard();
        $plugboard->setCableConnections(
            [
                'A' => 'T',
                'B' => 'L',
                'D' => 'F',
                'G' => 'J',
                'H' => 'M',
                'N' => 'W',
                'O' => 'P',
                'Q' => 'Y',
                'R' => 'Z',
                'V' => 'X'
            ]
        );

        $machine->setRotorSlots($rotorSlots);
        $machine->setPawls($pawls);
        $machine->setPlugboard($plugboard);
        $machine->setReflector($reflector);

        $rawInput = "NCZW VUSX PNYM INHZ XMQX SFWX WLKJ AHSH NMCO CCAK UQPM KCSM HKSE INJU SBLK IOSX CKUB HMLL XCSJ USRR DVKO HULX WCCB GVLI YXEO AHXR HKKF VDRE WEZL XOBA FGYU JQUK GRTV UKAM EURB VEKS UHHV OYHA BCJW MAKL FKLM YFVN RIZR VVRT KOFD ANJM OLBG FFLE OPRG TFLV RHOW OPBE KVWM UQFM PWPA RMFH AGKX IIBG";

        $messageString = preg_replace('/\W/', '', $rawInput);
        $messageArray = preg_split('//', $messageString, -1, PREG_SPLIT_NO_EMPTY);

        $out = [];

        foreach ($messageArray as $inChar) {
            $out[] = $machine->getOutputCharacterForInputCharacter($inChar);
        }
        $enciphered = join('', $out);

        $formattedOutput = "VONV ONJL OOKS JHFF TTTE INSE INSD REIZ WOYY QNNS NEUN INHA LTXX BEIA NGRI FFUN TERW ASSE RGED RUEC KTYW ABOS XLET ZTER GEGN ERST ANDN ULAC HTDR EINU LUHR MARQ UANT ONJO TANE UNAC HTSE YHSD REIY ZWOZ WONU LGRA DYAC HTSM YSTO
 SSEN ACHX EKNS VIER MBFA
 ELLT YNNN NNNO OOVI ERYS
 ICHT EINS NULL";

        $plainOutput = preg_replace('/\W/', '', $formattedOutput);

        $this->assertSame($plainOutput, $enciphered);
    }
}
