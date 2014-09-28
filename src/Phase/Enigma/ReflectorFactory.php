<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 28/09/14
 * Time: 11:51
 */

namespace Phase\Enigma;


class ReflectorFactory
{

    const REFLECTOR_B = 'B';
    const REFLECTOR_C = 'C';
    const REFLECTOR_B_THIN = 'Bd';
    const REFLECTOR_C_THIN = 'Cd';

    protected $halfReflectorSpecs = [
        self::REFLECTOR_B => [
            'A' => 'Y',
            'B' => 'R',
            'C' => 'U',
            'D' => 'H',
            'E' => 'Q',
            'F' => 'S',
            'G' => 'L',
            'I' => 'P',
            'J' => 'X',
            'K' => 'N',
            'M' => 'O',
            'T' => 'Z',
            'V' => 'W'
        ],
        self::REFLECTOR_C => [
            'A' => 'F',
            'B' => 'V',
            'C' => 'P',
            'D' => 'J',
            'E' => 'I',
            'G' => 'O',
            'H' => 'Y',
            'K' => 'R',
            'L' => 'Z',
            'M' => 'X',
            'N' => 'W',
            'T' => 'Q',
            'S' => 'U'
        ],
        self::REFLECTOR_B_THIN => [
            'A' => 'E',
            'B' => 'N',
            'C' => 'K',
            'D' => 'Q',
            'F' => 'U',
            'G' => 'Y',
            'H' => 'W',
            'I' => 'J',
            'L' => 'O',
            'M' => 'P',
            'R' => 'X',
            'S' => 'Z',
            'T' => 'V',
        ],
        self::REFLECTOR_C_THIN => [
            'A' => 'R',
            'B' => 'D',
            'C' => 'O',
            'E' => 'J',
            'F' => 'N',
            'G' => 'T',
            'H' => 'K',
            'I' => 'V',
            'L' => 'M',
            'P' => 'W',
            'Q' => 'Z',
            'S' => 'X',
            'U' => 'Y',
        ]
    ];

    /**
     * @param $instanceId
     * @return Reflector
     */
    public function buildReflectorInstance($instanceId)
    {
        $reflector = new Reflector();
        $reflector->setHalfMapping($this->halfReflectorSpecs[$instanceId]);

        return $reflector;
    }

    public function getSupportedReflectorIdentities()
    {
        return (array_keys($this->halfReflectorSpecs));
    }
}
