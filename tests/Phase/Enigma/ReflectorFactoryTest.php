<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 28/09/14
 * Time: 12:00
 */

namespace Phase\Enigma;


class ReflectorFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Ensure that all supported reflector IDs can be built into reflectors
     */
    public function testSupportedReflectorsList()
    {
        $factory = new ReflectorFactory();
        $reflectorIds = $factory->getSupportedReflectorIdentities();
        foreach ($reflectorIds as $reflectorId) {
            $reflector = $factory->buildReflectorInstance($reflectorId);
            $this->assertTrue($reflector instanceof Reflector);
        }
    }
}
