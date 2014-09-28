<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 25/09/14
 * Time: 10:38
 */

namespace Phase\Enigma;


interface EncryptorInterface
{

    /**
     * Return the output for the given encryptor input in its current state
     *
     * @param string $inputCharacter Single character, uppercase
     * @return string Single character, uppercase
     */
    public function getOutputCharacterForInputCharacter($inputCharacter);
}
