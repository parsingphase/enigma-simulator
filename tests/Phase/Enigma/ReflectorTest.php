<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 28/09/14
 * Time: 11:08
 */

namespace Phase\Enigma;


class ReflectorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param array[] $halfMapping
     * @param string $inChar
     * @param string $expectedOutChar
     * @dataProvider mappingTestDataProvider
     */
    public function testCreateAndConfigureRotor($halfMapping, $inChar, $expectedOutChar)
    {
        $reflector = new Reflector;
        $this->assertTrue($reflector instanceof Reflector);
        $reflector->setHalfMapping($halfMapping);
        $outChar = $reflector->getOutputCharacterForInputCharacter($inChar);
        $this->assertSame($expectedOutChar, $outChar);
    }

    public function mappingTestDataProvider()
    {
        $halfMappingB = [
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
        ];

        $data = [];

        //test specified mapping
        $data[] = [$halfMappingB, 'I', 'P'];
        $data[] = [$halfMappingB, 'Z', 'T'];

        return $data;
    }

    /**
     * @param $halfMapping
     * @dataProvider invalidHalfMappingProvider
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidRotorHalfMappings($halfMapping)
    {
        $reflector = new Reflector();
        $reflector->setHalfMapping($halfMapping);
    }

    public function invalidHalfMappingProvider()
    {
        $badMappings = [
            [null], // not array
            [['A']], // not an associative array
            [   // too short
                ['A' => 'Y', 'B' => 'R', 'C' => 'U', 'D' => 'H', 'E' => 'Q', 'F' => 'S', 'G' => 'L', 'I' => 'P']
            ],
            [   // mapping to self
                [
                    'A' => 'A',
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
                ]
            ],
            [   // Same char in 2 mappings
                [
                    'A' => 'Y',
                    'B' => 'A',
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
                ]
            ],
            [ // non-char elements
                1 => 25,
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
            ]

        ];
        return $badMappings;
    }
}
