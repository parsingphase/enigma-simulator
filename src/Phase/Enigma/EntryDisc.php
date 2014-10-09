<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 09/10/14
 * Time: 20:42
 */

namespace Phase\Enigma;


class EntryDisc implements EncryptorInterfaceReversible
{

    /**
     * @var array[] full 26-char=>char array
     */
    protected $mapping;

    /**
     * @return \array[]
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * @param \array[] $mapping
     */
    public function setMapping($mapping)
    {
        $this->mapping = $mapping;
    }

    /**
     * Return the output for the given encryptor input in its current state
     *
     * @param string $inputCharacter Single character, uppercase
     * @return string Single character, uppercase
     */
    public function getOutputCharacterForInputCharacter($inputCharacter)
    {
        $inputCharacter = strtoupper($inputCharacter);

        if (!preg_match('/^[A-Z]$/', $inputCharacter)) {
            throw new \InvalidArgumentException("Received '$inputCharacter' as input character, must be A-Z ");
        }
        return $this->mapping[$inputCharacter];
    }

    /**
     * Return the output for the given encryptor input in its current state, swapping input and output terminals
     *
     * @param string $inputCharacter Single character, uppercase
     * @return string Single character, uppercase
     */
    public function getOutputCharacterForInputCharacterReversedSignal($inputCharacter)
    {
        $inputCharacter = strtoupper($inputCharacter);

        if (!preg_match('/^[A-Z]$/', $inputCharacter)) {
            throw new \InvalidArgumentException("Received '$inputCharacter' as input character, must be A-Z ");
        }
        $reverseMapping = array_flip($this->mapping);
        return $reverseMapping[$inputCharacter];
    }
}
