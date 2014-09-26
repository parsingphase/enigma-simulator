<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 26/09/14
 * Time: 14:42
 */

namespace Phase\Enigma;


class RotorFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testFactoryBuildsRotorsWithValidName()
    {
        $factory = new RotorFactory();
        $this->assertTrue($factory instanceof RotorFactory);

        $rotor = $factory->buildRotorInstance(RotorFactory::ROTOR_ONE);
        $this->assertTrue($rotor instanceof Rotor);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFactoryRejectsBuildsWithBadName()
    {
        $factory = new RotorFactory();
        $this->assertTrue($factory instanceof RotorFactory);

        $factory->buildRotorInstance('NOSUCHROTOR');
    }


    /**
     * Make sure each rotor's mapping is coherent
     * @dataProvider supportedRotorsDataProvider
     *
     * @param Rotor $rotor Rotor to test
     */
    function testCoherentRotorIdentities(Rotor $rotor)
    {
        $mapping = $rotor->getCoreMapping();
        $this->assertTrue(is_array($mapping));
        $this->assertSame(26, count($mapping));

        $inputsSeen = array();
        $outputsSeen = array();

        foreach ($mapping as $i => $o) {
            $this->assertFalse(isset($inputsSeen[$i]), 'Input seen before');
            $this->assertFalse(isset($outputsSeen[$o]), 'Output seen before');
            $this->assertSame(
                1,
                preg_match('/^[A-Z]$/', $i),
                'Input "' . $i . '" must be upper-case letter'
            );
            $this->assertSame(
                1,
                preg_match('/^[A-Z]$/', $o),
                'Output must be upper-case letter'
            );
            $inputsSeen[$i] = true;
            $outputsSeen[$o] = true;
        }

        $this->assertSame(26, count($inputsSeen));
        $this->assertSame(26, count($outputsSeen));
    }

    public function supportedRotorsDataProvider()
    {
        $factory = new RotorFactory();
        $rotorIds = $factory->getSupportedRotorIdentities();
        $parameterLists = [];
        foreach ($rotorIds as $rotorId) {
            $parameterLists[] = [$factory->buildRotorInstance($rotorId)];
        }
        return $parameterLists;
    }

    /**
     * Make sure each rotor's notch positions are coherent
     * @dataProvider supportedRotorsDataProvider
     *
     * @param Rotor $rotor Rotor to test
     */
    public function testAllRotorNotches(Rotor $rotor)
    {
        $notches = $rotor->getNotchPositions();
        $this->assertTrue(is_array($notches));
        $this->assertTrue(count($notches) < 3);
        foreach ($notches as $position) {
            $this->assertRegExp('/^[A-Z]$/', $position);
        }
    }

}
