<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 10/10/14
 * Time: 09:13
 */

namespace Phase\Enigma;


class EntryDiscFactory
{
    const ENTRYDISC_ALPHABET = 1; // M-series discs
    const ENTRYDISC_QWERTZU = 2; // Pre-war commercial
    const ENTRYDISC_TIRPITZ = 3; // Type T Japanese disc

    protected $discSpecs = [
        self::ENTRYDISC_ALPHABET => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        self::ENTRYDISC_QWERTZU => 'QWERTZUIOASDFGHJKPYXCVBNML',
        self::ENTRYDISC_TIRPITZ => 'KZROUQHYAIGBLWVSTDXFPNMCJE'
    ];

    /**
     * @param $instanceId
     * @return Reflector
     */
    public function buildEntryDiscInstance($instanceId)
    {
        $entryDisc = new EntryDisc();
        $mapping = $this->buildMappingArray($this->discSpecs[$instanceId]);
        $entryDisc->setMapping($mapping);

        return $entryDisc;
    }

    public function getSupportedReflectorIdentities()
    {
        return (array_keys($this->discSpecs));
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
