<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 10/10/14
 * Time: 10:12
 */

namespace Phase\Enigma;


class EntryDiscFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateDeclaredDiscs()
    {
        $factory = new EntryDiscFactory();
        $discIds = $factory->getSupportedReflectorIdentities();
        foreach ($discIds as $discId) {
            $disc = $factory->buildEntryDiscInstance($discId);
            $this->assertTrue($disc instanceof EntryDisc);
        }
    }


    /**
     * @param $disc
     * @param $in
     * @param $out
     *
     * @dataProvider mappingTestProvider
     */
    public function testNonIdentityMapping(EntryDisc $disc, $in, $out)
    {
        $this->assertSame($out, $disc->getOutputCharacterForInputCharacter($in));
        $this->assertSame($in, $disc->getOutputCharacterForInputCharacterReversedSignal($out));
    }

    public function mappingTestProvider()
    {
        $discId = EntryDiscFactory::ENTRYDISC_QWERTZU; // http://www.cryptomuseum.com/crypto/enigma/d/index.htm
        $factory = new EntryDiscFactory();

        $disc = $factory->buildEntryDiscInstance($discId);
        return [
            [$disc, 'A', 'Q'],
            [$disc, 'Z', 'L'],
            [$disc, 'G', 'U'],
        ];
    }
}
