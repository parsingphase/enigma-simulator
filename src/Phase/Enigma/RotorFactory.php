<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 26/09/14
 * Time: 14:35
 */

namespace Phase\Enigma;


class RotorFactory
{

    const ROTOR_ONE = 1;
    const ROTOR_TWO = 2;
    const ROTOR_THREE = 3;
    const ROTOR_FOUR = 4;
    const ROTOR_FIVE = 5;
    const ROTOR_SIX = 6;
    const ROTOR_SEVEN = 7;
    const ROTOR_EIGHT = 8;
    const ROTOR_BETA = 'B';
    const ROTOR_GAMMA = 'G';

    /**
     * Simple representation of output lists against inputs A-Z
     *
     * @var array
     */
    protected $rotorSpecStrings = [
        self::ROTOR_ONE => 'EKMFLGDQVZNTOWYHXUSPAIBRCJ',
        self::ROTOR_TWO => 'AJDKSIRUXBLHWTMCQGZNPYFVOE',
        self::ROTOR_THREE => 'BDFHJLCPRTXVZNYEIWGAKMUSQO',
        self::ROTOR_FOUR => 'ESOVPZJAYQUIRHXLNFTGKDCMWB',
        self::ROTOR_FIVE => 'VZBRGITYUPSDNHLXAWMJQOFECK',
        self::ROTOR_SIX => 'JPGVOUMFYQBENHZRDKASXLICTW',
        self::ROTOR_SEVEN => 'NZJHGRCXMYSWBOUFAIVLPEKQDT',
        self::ROTOR_EIGHT => 'FKQHTLXOCBJSPDZRAMEWNIUYGV',
        self::ROTOR_BETA => 'LEYJVCNIXWPBQMDRTAKZGFUHOS',
        self::ROTOR_GAMMA => 'FSOKANUERHMBTIYCWLQPZXVGJD'
    ];


    protected $rotorNotchPositions = [
        self::ROTOR_ONE => ['Q'],
        self::ROTOR_TWO => ['E'],
        self::ROTOR_THREE => ['V'],
        self::ROTOR_FOUR => ['J'],
        self::ROTOR_FIVE => ['Z'],
        self::ROTOR_SIX => ['Z', 'M'],
        self::ROTOR_SEVEN => ['Z', 'M'],
        self::ROTOR_EIGHT => ['Z', 'M'],
        self::ROTOR_BETA => [],
        self::ROTOR_GAMMA => []
    ];

    /**
     * @param mixed $instanceId A rotor name from the self::ROTOR_* constant list
     * @return Rotor
     */
    public function buildRotorInstance($instanceId)
    {
        $rotorSpecString = $this->getRotorSpecString($instanceId);
        $outputs = preg_split('//', $rotorSpecString, -1, PREG_SPLIT_NO_EMPTY);
        $inputs = preg_split('//', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', -1, PREG_SPLIT_NO_EMPTY);
        $coreMapping = array_combine($inputs, $outputs);
        $rotor = new Rotor();
        $rotor->setCoreMapping($coreMapping);
        $rotor->setNotchPositions($this->rotorNotchPositions[$instanceId]);
        $rotor->setRingOffset('A'); // useful default
        return $rotor;
    }

    public function getSupportedRotorIdentities()
    {
        return array_keys($this->rotorSpecStrings);
    }

    protected function getRotorSpecString($rotorId)
    {
        if (isset($this->rotorSpecStrings[$rotorId])) {
            return $this->rotorSpecStrings[$rotorId];
        } else {
            throw new \InvalidArgumentException;
        }
    }
}
