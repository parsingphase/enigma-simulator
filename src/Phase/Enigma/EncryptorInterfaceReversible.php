<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 28/09/14
 * Time: 14:10
 */

namespace Phase\Enigma;


interface EncryptorInterfaceReversible extends EncryptorInterface {

    /**
     * Return the output for the given encryptor input in its current state, swapping input and output terminals
     *
     * @param string $inputCharacter Single character, uppercase
     * @return string Single character, uppercase
     */
    public function getOutputCharacterForInputCharacterReversedSignal($inputCharacter);
}
