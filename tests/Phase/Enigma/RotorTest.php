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
    public function testRotorIsAnEncryptor ()
    {
        $rotor = new Rotor();
        $this->assertTrue($rotor instanceof EncryptorInterface, "Rotor must support EncryptorInterface");
    }
}
