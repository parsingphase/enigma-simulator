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
    use RotaryAlphaNumericTrait;

    /**
     * @var int Position of the ring relative to the core
     */
    protected $ringOffset;

    /**
     * @var array 26-element character-indexed array of input to output
     */
    protected $coreMapping;

    /**
     * @var string[] One or two single char positions determining the notch offsets
     */
    protected $notchPositions;

    /**
     * @return string
     */
    public function getRingOffset()
    {
        return $this->ringOffset;
    }

    /**
     * @param string|int $ringOffset
     */
    public function setRingOffset($ringOffset)
    {
        if (preg_match('/^[A-Z]$/', $ringOffset)) {
            $ringOffset = $this->charToAlphabetPosition($ringOffset);
        }

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
     * @return \string[]
     */
    public function getNotchPositions()
    {
        return $this->notchPositions;
    }

    /**
     * @param \string[] $notchPositions
     */
    public function setNotchPositions($notchPositions)
    {
        if (is_array($notchPositions) && (count($notchPositions) < 3)) {
            foreach ($notchPositions as $position) {
                if (!preg_match('/^[A-Z]$/', $position)) {
                    throw new \InvalidArgumentException;
                }
            }
            $this->notchPositions = $notchPositions;
        } else {
            throw new \InvalidArgumentException;
        }
    }

    /**
     * Return the output for the given encryptor input in its current state
     *
     * @param string $inputCharacter Single character, uppercase
     * @return string Single character, uppercase
     */
    public function getOutputCharacterForInputCharacter($inputCharacter)
    {
        $outputCharacter = $this->encipherCharacterDirectional($inputCharacter, true);
        return ($outputCharacter);
    }

    /**
     * Return the output for the given encryptor input in its current state
     *
     * @param string $inputCharacter Single character, uppercase
     * @return string Single character, uppercase
     */
    public function getOutputCharacterForInputCharacterReversedSignal($inputCharacter)
    {
        $outputCharacter = $this->encipherCharacterDirectional($inputCharacter, false);
        return ($outputCharacter);
    }

    /**
     * @param $inputCharacter
     * @return string
     */
    protected function encipherCharacterDirectional($inputCharacter, $forwardDirection = true)
    {
        $inputCharacter = strtoupper($inputCharacter);

        if (!preg_match('/^[A-Z]$/', $inputCharacter)) {
            throw new \InvalidArgumentException("Received '$inputCharacter' as input character, must be A-Z ");
        }

        $coreInputCharacter = $this->incrementCharacterByOffset(
            $inputCharacter,
            26 - ($this->ringOffset - 1)
        );

        $mapping = $forwardDirection ? $this->coreMapping : array_flip($this->coreMapping);

        $coreOutputCharacter = $mapping[$coreInputCharacter];

        $outputCharacter = $this->incrementCharacterByOffset(
            $coreOutputCharacter,
            ($this->ringOffset - 1)
        );

        return $outputCharacter;
    }
}
