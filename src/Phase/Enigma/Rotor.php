<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 25/09/14
 * Time: 10:24
 */
namespace Phase\Enigma;

class Rotor implements EncryptorInterface
{
    /**
     * @var string Single letter; Position of the ring relative to the core
     */
    protected $ringOffset;


    /**
     * @var array 26-element character-indexed array of input to output
     */
    protected $coreMapping;

    /**
     * @return string
     */
    public function getRingOffset()
    {
        return $this->ringOffset;
    }

    /**
     * @param string $ringOffset
     */
    public function setRingOffset($ringOffset)
    {
        if (!is_integer($ringOffset) || ($ringOffset < 1) || ($ringOffset > 26)) {
            throw new \InvalidArgumentException("Offset must be integer in range 1..26");
        }
        $this->ringOffset = $ringOffset;
    }

    /**
     * @return array
     */
    public function getCoreMapping()
    {
        return $this->coreMapping;
    }

    /**
     * @param array $coreMapping
     */
    public function setCoreMapping($coreMapping)
    {
        if (count($coreMapping) != 26) {
            throw new \InvalidArgumentException("Mapping must have 26 elements");
        }

        $this->coreMapping = $coreMapping;
    }

    /**
     * Return the output for the given encryptor input in its current state
     *
     * @param string $inputCharacter Single character, uppercase
     * @return string Single character, uppercase
     */
    public function getOutputCharacterForInputCharacter ($inputCharacter)
    {
        $inputCharacter = strtoupper($inputCharacter);

        $coreInputCharacter = $this->getCharacterOffsetBy(
            $inputCharacter,
            $this->ringOffset - 1
        );

        $coreOutputCharacter
            = $this->coreMapping[$coreInputCharacter];

        $outputCharacter = $this->getCharacterOffsetBy(
            $coreOutputCharacter,
            0 - ($this->ringOffset - 1)
        );

        return ($outputCharacter);
    }

    protected function getCharacterOffsetBy($character, $offset)
    {
        $charAsInt =
            $this->charToAlphabetPosition($character);

        $newInteger = (26 + $charAsInt - $offset) % 26;

        if ($newInteger == 0) {
            $newInteger = 26;
        }

        $newCharacter =
            $this->alphabetPositionToCharacter($newInteger);

        return ($newCharacter);
    }

    protected function charToAlphabetPosition($char)
    {
        $position = (ord($char) - 64);

        return $position;
    }

    protected function alphabetPositionToCharacter($position)
    {
        $char = (chr($position + 64));

        return $char;
    }
}
