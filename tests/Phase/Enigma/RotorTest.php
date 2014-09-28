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

    function testCoreIdentityMappingReturnsInputAtDefaultOffset()
    {
        $rotor = new Rotor();
        $coreMapping=array(
            'A'=>'A','B'=>'B','C'=>'C','D'=>'D','E'=>'E','F'=>'F','G'=>'G',
            'H'=>'H','I'=>'I','J'=>'J','K'=>'K','L'=>'L','M'=>'M','N'=>'N',
            'O'=>'O','P'=>'P','Q'=>'Q','R'=>'R','S'=>'S','T'=>'T','U'=>'U',
            'V'=>'V','W'=>'W','X'=>'X','Y'=>'Y','Z'=>'Z'
        );
        $offset=1; //anything will do for now
        $rotor->setRingOffset($offset);
        $rotor->setCoreMapping($coreMapping);

        $testCharacter='V';

        $this->assertSame(
            $testCharacter,
            $rotor->getOutputCharacterForInputCharacter($testCharacter)
        );
    }


    public function testRot13Mapping()
    {
        $rotor = new Rotor();
        $coreMapping=array(
            'A'=>'N','B'=>'O','C'=>'P','D'=>'Q','E'=>'R','F'=>'S','G'=>'T',
            'H'=>'U','I'=>'V','J'=>'W','K'=>'X','L'=>'Y','M'=>'Z','N'=>'A',
            'O'=>'B','P'=>'C','Q'=>'D','R'=>'E','S'=>'F','T'=>'G','U'=>'H',
            'V'=>'I','W'=>'J','X'=>'K','Y'=>'L','Z'=>'M'
        );
        $offset=1; // doesn't matter for this rotor
        $rotor->setRingOffset($offset);
        $rotor->setCoreMapping($coreMapping);

        $testInputCharacter='V';
        $testOutputCharacter='I';

        $this->assertSame(
            $testOutputCharacter,
            $rotor->getOutputCharacterForInputCharacter($testInputCharacter)
        );
    }


    /**
     * Test routing of data through ring one at various offsets
     * @param int $offset
     * @param string $testInputCharacter
     * @param string $testOutputCharacter
     * @dataProvider rotorIDataProvider
     */
    function testRotorIMappingOffset($offset, $testInputCharacter, $testOutputCharacter)
    {
        $rotor = new Rotor();
        $coreMapping=array(
            'A'=>'E','B'=>'K','C'=>'M','D'=>'F','E'=>'L','F'=>'G','G'=>'D',
            'H'=>'Q','I'=>'V','J'=>'Z','K'=>'N','L'=>'T','M'=>'O','N'=>'W',
            'O'=>'Y','P'=>'H','Q'=>'X','R'=>'U','S'=>'S','T'=>'P',
            'U'=>'A','V'=>'I','W'=>'B','X'=>'R','Y'=>'C','Z'=>'J'
        );

        $rotor->setRingOffset($offset);
        $rotor->setCoreMapping($coreMapping);


        $this->assertSame($testOutputCharacter,
            $rotor->getOutputCharacterForInputCharacter($testInputCharacter));

        $this->assertSame($testInputCharacter,
            $rotor->getOutputCharacterForInputCharacterReversedSignal($testOutputCharacter));
    }

    public function rotorIDataProvider()
    {
        return [
            [1, 'V', 'I'],
            [2, 'V', 'B'],
            [16, 'G', 'J']
        ];
    }


    /**
     * Try and encrypt something invalid
     * @dataProvider badCharacterIDataProvider
     * @expectedException \InvalidArgumentException
     */
    public function testEncryptBadCharacter($characters)
    {
        $rotor = new Rotor();
        $coreMapping = [
            'A'=>'E','B'=>'K','C'=>'M','D'=>'F','E'=>'L','F'=>'G','G'=>'D',
            'H'=>'Q','I'=>'V','J'=>'Z','K'=>'N','L'=>'T','M'=>'O','N'=>'W',
            'O'=>'Y','P'=>'H','Q'=>'X','R'=>'U','S'=>'S','T'=>'P','U'=>'A',
            'V'=>'I','W'=>'B','X'=>'R','Y'=>'C','Z'=>'J'
        ];
        $offset = 0;
        $rotor->setRingOffset($offset);
        $rotor->setCoreMapping($coreMapping);

        // and this should cause the exception:
        $rotor->getOutputCharacterForInputCharacter($characters);
    }


    public function badCharacterIDataProvider()
    {
        return [[' '], [''], [2], ['toolong']];
    }


    /**
     * Ensure we accept character ring offset values
     *
     * @dataProvider validRingOffsetProvider
     */
    public function testSetAndRememberCharacterRingOffset()
    {
        $rotor = new Rotor();
        $rotor->setRingOffset('M');
        $this->assertSame(13, $rotor->getRingOffset());
    }

    /**
     * @dataProvider validNotchSettingsProvider
     * @param string[] $notchPositions One or more character positions
     */
    public function testValidNotchSetter($notchPositions)
    {
        $rotor = new Rotor();
        $rotor->setNotchPositions($notchPositions);
        $this->assertSame($notchPositions, $rotor->getNotchPositions());
    }

    public function validNotchSettingsProvider()
    {
        return [
            [['A']], // allow one notch
            [['A', 'J']], // allow two notches
            [['Z']], // check the upper limit
            [[]] // allow empty array
        ];
    }

    /**
     * @dataProvider invalidNotchSettingsProvider
     * @param string[] $notchPositions One or more character positions
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidNotchSetter($notchPositions)
    {
        $rotor = new Rotor();
        $rotor->setNotchPositions($notchPositions);
    }


    public function invalidNotchSettingsProvider()
    {
        return [
            [['']], // non-character
            [['AB']], // non-single-character
            [['A', 'J', 'Z']], // don't allow three notches
        ];
    }

}
