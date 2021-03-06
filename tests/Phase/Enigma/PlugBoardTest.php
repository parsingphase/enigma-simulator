<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 27/09/14
 * Time: 18:27
 */

namespace Phase\Enigma;


class PlugboardTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test various cable combinations, swapped and non-swapped characters
     * @dataProvider connectionsDataProvider
     * @param $connections Array pairs of letters
     * @param $in String single character
     * @param $out String single character
     * @return null
     */
    public function testSubstitutions($connections, $in, $out)
    {
        $plugboard = new Plugboard();
        $plugboard->setCableConnections($connections);

        $this->assertSame(
            $out,
            $plugboard->getOutputCharacterForInputCharacter($in),
            "Plugboard must substitute pair as specified"
        );
        $this->assertSame(
            $in,
            $plugboard->getOutputCharacterForInputCharacter($out),
            "Plugboard substitution must be reciprocal"
        );
    }

    public function connectionsDataProvider()
    {
        $testData = [
            [[], 'A', 'A'], // Test identity mapping with no cables plugged
            [   // set cables and an input that will swap
                [
                    'D' => 'N',
                    'G' => 'R',
                    'I' => 'S',
                    'K' => 'C',
                    'Q' => 'X',
                    'T' => 'M',
                    'P' => 'V',
                    'H' => 'Y',
                    'F' => 'W',
                    'B' => 'J'
                ],
                'K',
                'C',
            ],
            [ // set cables and an input that won't swap
                [
                    'D' => 'N',
                    'G' => 'R',
                    'I' => 'S',
                    'K' => 'C',
                    'Q' => 'X',
                    'T' => 'M',
                    'P' => 'V',
                    'H' => 'Y',
                    'F' => 'W',
                    'B' => 'J'
                ],
                'E',
                'E',
            ],
            [
                [
                    'A' => 'T',
                    'B' => 'L',
                    'D' => 'F',
                    'G' => 'J',
                    'H' => 'M',
                    'N' => 'W',
                ],
                'H',
                'M',
            ],
            [
                [
                    'A' => 'T',
                    'B' => 'L',
                    'D' => 'F',
                    'G' => 'J',
                    'H' => 'M',
                    'N' => 'W',
                ],
                'C',
                'C',
            ]
        ];

        return ($testData);
    }

    /**
     * @dataProvider badConnectionsDataProvider
     * @param array $mapping
     * @expectedException \InvalidArgumentException
     */
    public function testBadConfigurations($mapping)
    {
        $plugboard = new Plugboard();
        $this->assertTrue($plugboard instanceof Plugboard);
        $plugboard->setCableConnections($mapping);
    }

    public function badConnectionsDataProvider()
    {
        return [
            [['A' => 'B', 'C' => 'B']],
            [['A' => 'B', '' => 'D']],
        ];
    }

    /**
     * Test adding the same 'from' connection twice, which we can't test via setCableConnections as array can't have dup keys
     * @expectedException \InvalidArgumentException
     */
    public function testRejectDuplicateConnection()
    {
        $plugboard = new Plugboard();
        $plugboard->addCableConnection('A', 'B');
        $plugboard->addCableConnection('A', 'C');
    }
}
