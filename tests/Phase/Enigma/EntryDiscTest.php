<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 09/10/14
 * Time: 20:49
 */

namespace Phase\Enigma;


class EntryDiscTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateValidIdentityMappingDisc()
    {
        $mapping = $this->buildIdentityMapping();
        $disc = new EntryDisc();
        $this->assertTrue($disc instanceof EntryDisc);
        $this->assertTrue($disc instanceof EncryptorInterfaceReversible);

        $disc->setMapping($mapping);

        $this->assertSame($mapping, $disc->getMapping());
    }

    /**
     * @param $in
     * @param $out
     * @dataProvider unityMappingProvider
     */
    public function testIdentityMappingDisc($in, $out)
    {
        $mapping = $this->buildIdentityMapping();
        $disc = new EntryDisc();
        $disc->setMapping($mapping);
        $this->assertSame($out, $disc->getOutputCharacterForInputCharacter($in));
        $this->assertSame($in, $disc->getOutputCharacterForInputCharacterReversedSignal($out));
    }

    public function unityMappingProvider()
    {
        return [
            ['A', 'A'],
            ['Q', 'Q'],
            ['Z', 'Z'],
        ];
    }

    /**
     * @param $in
     * @dataProvider badInputProvider
     * @expectedException \InvalidArgumentException
     */
    public function testIdentityMappingDiscBadInputs($in)
    {
        $mapping = $this->buildIdentityMapping();
        $disc = new EntryDisc();
        $disc->setMapping($mapping);
        $disc->getOutputCharacterForInputCharacter($in);
    }

    /**
     * @param $in
     * @dataProvider badInputProvider
     * @expectedException \InvalidArgumentException
     */
    public function testIdentityMappingDiscBadInputsReverse($in)
    {
        $mapping = $this->buildIdentityMapping();
        $disc = new EntryDisc();
        $disc->setMapping($mapping);
        $disc->getOutputCharacterForInputCharacterReversedSignal($in);
    }

    public function badInputProvider()
    {
        return [
            [1],
            [''],
            [null],
        ];
    }

    /**
     * @return array
     */
    protected function buildIdentityMapping()
    {
        $mappingValueString = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return $this->buildMappingArray($mappingValueString);
    }


    /**
     * @param $mappingString
     * @param $in
     * @param $out
     *
     * @dataProvider mappingTestProvider
     */
    public function testNonIdentityMapping($mappingString, $in, $out)
    {
        $mapping = $this->buildMappingArray($mappingString);
        $disc = new EntryDisc();
        $disc->setMapping($mapping);

        $this->assertSame($out, $disc->getOutputCharacterForInputCharacter($in));
        $this->assertSame($in, $disc->getOutputCharacterForInputCharacterReversedSignal($out));
    }

    public function mappingTestProvider()
    {
        $commercialMap = 'QWERTZUIOASDFGHJKPYXCVBNML'; // http://www.cryptomuseum.com/crypto/enigma/d/index.htm
        return [
            [$commercialMap, 'A', 'Q'],
            [$commercialMap, 'Z', 'L'],
            [$commercialMap, 'G', 'U'],
        ];
    }

    /**
     * @param $mappingValueString
     * @return array
     */
    protected function buildMappingArray($mappingValueString)
    {
        $keyString = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $keys = preg_split('//', $keyString, -1, PREG_SPLIT_NO_EMPTY);
        $values = preg_split('//', $mappingValueString, -1, PREG_SPLIT_NO_EMPTY);

        $mapping = array_combine($keys, $values);
        return $mapping;
    }
}
