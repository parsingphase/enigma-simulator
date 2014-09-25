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

    public function testSetAndRememberRingOffset()
    {
        $rotor = new Rotor();
        $ringOffset = 15;
        $rotor->setRingOffset($ringOffset);
        $this->assertSame($ringOffset, $rotor->getRingOffset());
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
