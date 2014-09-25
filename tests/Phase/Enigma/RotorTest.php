<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 25/09/14
 * Time: 10:17
 */

namespace Phase\Enigma;


class RotorTest extends \PHPUnit_Framework_TestCase
{
    public function testRotorIsAnEncryptor()
    {
        $rotor = new Rotor();
        $this->assertTrue($rotor instanceof EncryptorInterface, "Rotor must support EncryptorInterface");
    }

    /**
     * Ensure we accept a range of valid ring offset values
     *
     * @dataProvider validRingOffsetProvider
     * @param int $ringOffset
     */
    public function testSetAndRememberRingOffset($ringOffset)
    {
        $rotor = new Rotor();
        $rotor->setRingOffset($ringOffset);
        $this->assertSame($ringOffset, $rotor->getRingOffset());
    }

    public function validRingOffsetProvider()
    {
        return [
            [1], // minimum good value
            [15], // our previous mid-range good value
            [26] // our maximum good value
        ];
    }

    /**
     * Ensure we accept a range of valid ring offset values
     *
     * @dataProvider badRingOffsetProvider
     * @expectedException \InvalidArgumentException
     * @param int $ringOffset
     */
    public function testRejectBadRingOffset($ringOffset)
    {
        $rotor = new Rotor();
        $rotor->setRingOffset($ringOffset);
    }

    public function badRingOffsetProvider()
    {
        return [
            [0], // just too low
            [27], // just too high
            [0.99], // Not integer, and too low
            [1.99], // Not integer, but in range
            ['A QA engineer walks into a bar'] // just wrong
        ];
    }


//    public function testSetAndRememberCoreMapping ()
//    {
//        $rotor = new Rotor();
//        $coreMapping=array();
//        $rotor->setCoreMapping($coreMapping);
//        $this->assertSame($coreMapping,$rotor->getCoreMapping());
//
//    }

    public function testSetAndRememberValidCoreIdentityMapping ()
    {
        $rotor = new Rotor();
        $coreMapping=array(
            'A'=>'A','B'=>'B','C'=>'C','D'=>'D','E'=>'E','F'=>'F','G'=>'G',
            'H'=>'H','I'=>'I','J'=>'J','K'=>'K','L'=>'L','M'=>'M','N'=>'N',
            'O'=>'O','P'=>'P','Q'=>'Q','R'=>'R','S'=>'S','T'=>'T','U'=>'U',
            'V'=>'V','W'=>'W','X'=>'X','Y'=>'Y','Z'=>'Z'
        );
        $rotor->setCoreMapping($coreMapping);
        $this->assertSame($coreMapping,$rotor->getCoreMapping());
    }

    /**
     * Rotors should refuse to accept invalid arrays
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetInvalidCoreMappingThrowsException()
    {
        $this->setExpectedException('Exception');
        $rotor = new Rotor();
        $coreMapping=array(
            'A'=>'A','B'=>'B','C'=>'C','D'=>'D','E'=>'E','F'=>'F','G'=>'G',
            'H'=>'H','I'=>'I','J'=>'J','K'=>'K','L'=>'L','M'=>'M','N'=>'N',
            'O'=>'O','P'=>'P','Q'=>'Q','R'=>'R','S'=>'S','T'=>'T','U'=>'U',
            'V'=>'V','W'=>'W','X'=>'X','Y'=>'Y'
        );
        $rotor->setCoreMapping($coreMapping);
    }

}
