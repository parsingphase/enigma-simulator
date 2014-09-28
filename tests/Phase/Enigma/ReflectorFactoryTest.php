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
     * @param Reflector $reflector
     * @dataProvider supportedReflectorsDataProvider
     */
    public function testBuildAllReflectorsNoExceptions(Reflector $reflector)
    {
        $this->assertTrue($reflector instanceof EncryptorInterface);
    }

    public function supportedReflectorsDataProvider()
    {
        $factory = new ReflectorFactory();
        $rotorIds = $factory->getSupportedReflectorIdentities();
        $parameterLists = [];
        foreach ($rotorIds as $reflectorId) {
            $parameterLists[] = [$factory->buildReflectorInstance($reflectorId)];
        }
        return $parameterLists;
    }
}
