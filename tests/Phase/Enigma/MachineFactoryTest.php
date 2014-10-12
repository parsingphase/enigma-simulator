<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 12/10/14
 * Time: 15:47
 */

namespace Phase\Enigma;


class MachineFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateSpecifiedModels()
    {
        $factory = new MachineFactory();
        $models = $factory->getSupportedModels();
        $this->assertTrue(is_array($models));
        $this->assertTrue(count($models) > 0);
        foreach ($models as $model) {
            $machine = $factory->buildMachineInstance($model);
            $this->assertTrue($machine instanceof Machine);
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateUnsupportedModel()
    {
        $factory = new MachineFactory();
        $factory->buildMachineInstance('NONESUCH');
    }

    /**
     * Very trivial test to ensure that M3 is as fully built as expected (some parts require config)
     */
    public function testUseBuiltM3()
    {
        $factory = new MachineFactory();
        $machine = $factory->buildMachineInstance(MachineFactory::MODEL_M3);
        // need to add reflector and wheels for this to work
        $reflectorFactory = new ReflectorFactory();
        $reflector = $reflectorFactory->buildReflectorInstance(ReflectorFactory::REFLECTOR_B);
        $machine->setReflector($reflector);

        $rotorFactory = new RotorFactory();
        $slots = $machine->getRotorSlots();
        $slots[0]->loadRotor($rotorFactory->buildRotorInstance(RotorFactory::ROTOR_ONE));
        $slots[1]->loadRotor($rotorFactory->buildRotorInstance(RotorFactory::ROTOR_TWO));
        $slots[2]->loadRotor($rotorFactory->buildRotorInstance(RotorFactory::ROTOR_THREE));

        foreach ($slots as $slot) {
            $slot->setRotorOffset('A');
        }

        $signalOut = $machine->getOutputCharacterForInputCharacter('A');
        $this->assertNotSame($signalOut, 'A');
    }

    /**
     * Very trivial test to ensure that M3 is as fully built as expected (some parts require config)
     */
    public function testUseBuiltM4()
    {
        $factory = new MachineFactory();
        $machine = $factory->buildMachineInstance(MachineFactory::MODEL_M4);
        // need to add reflector and wheels for this to work
        $reflectorFactory = new ReflectorFactory();
        $reflector = $reflectorFactory->buildReflectorInstance(ReflectorFactory::REFLECTOR_B);
        $machine->setReflector($reflector);

        $rotorFactory = new RotorFactory();
        $slots = $machine->getRotorSlots();
        $slots[0]->loadRotor($rotorFactory->buildRotorInstance(RotorFactory::ROTOR_ONE));
        $slots[1]->loadRotor($rotorFactory->buildRotorInstance(RotorFactory::ROTOR_TWO));
        $slots[2]->loadRotor($rotorFactory->buildRotorInstance(RotorFactory::ROTOR_THREE));
        $slots[3]->loadRotor($rotorFactory->buildRotorInstance(RotorFactory::ROTOR_FOUR));

        foreach ($slots as $slot) {
            $slot->setRotorOffset('A');
        }

        $signalOut = $machine->getOutputCharacterForInputCharacter('A');
        $this->assertNotSame($signalOut, 'A');
    }
}
