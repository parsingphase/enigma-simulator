<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 28/09/14
 * Time: 11:18
 */

namespace Phase\Enigma;

/**
 * Simulates the physical reflector used in the Enigma machine
 * @package Phase\Enigma
 */
class Reflector implements EncryptorInterface
{
    /**
     * @var array[] full 26-char=>char array
     */
    protected $mapping;


    public function setHalfMapping($halfMapping)
    {
        if (!$this->isValidHalfMapping($halfMapping)) {
            throw new \InvalidArgumentException;
        }
        $this->mapping = $halfMapping + array_flip($halfMapping);
    }

    /**
     * Return the output for the given encryptor input in its current state
     *
     * @param string $inputCharacter Single character, uppercase
     * @return string Single character, uppercase
     */
    public function getOutputCharacterForInputCharacter($inputCharacter)
    {
        return $this->mapping[$inputCharacter];
    }

    protected function isValidHalfMapping($halfMapping)
    {
        $valid = false;
        if (is_array($halfMapping) && (count($halfMapping) == 13)) {
            $fullMapping = $halfMapping + array_flip($halfMapping);
            if (count($fullMapping) == 26) {
                $keysValuesOK = true;
                $seenKeys = $seenValues = [];
                foreach ($fullMapping as $k => $v) {
                    /** Removed this check as I can't actually see a way to trigger it!
                     * if (isset($seenKeys[$k]) || isset($seenValues[$v])) {
                     * $keysValuesOK = false;
                     * }
                     */
                    if (
                        (!preg_match('/^[A-Z]$/', $k)) ||
                        (!preg_match('/^[A-Z]$/', $v))
                    ) {
                        $keysValuesOK = false;
                    }

                    $seenKeys[$k] = true;
                    $seenValues[$v] = true;
                }
                $valid = $keysValuesOK;
            }
        }

        return $valid;
    }
}
