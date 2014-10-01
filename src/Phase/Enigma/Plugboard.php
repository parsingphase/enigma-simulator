<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 27/09/14
 * Time: 18:37
 */

namespace Phase\Enigma;


class Plugboard implements EncryptorInterface
{
    protected $connections = [];

    public function clearAllConnections()
    {
        $this->connections = [];
    }

    public function addCableConnection($fromCharacter, $toCharacter)
    {
        if (!(preg_match('/^[A-Z]$/', $fromCharacter) && preg_match('/^[A-Z]$/', $toCharacter))) {
            throw new \InvalidArgumentException("Invalid pair $fromCharacter-$toCharacter");
        }

        if (isset($this->connections[$fromCharacter])) {
            throw new \InvalidArgumentException("Socket $fromCharacter already used");
        }

        if (isset($this->connections[$toCharacter])) {
            throw new \InvalidArgumentException("Socket $toCharacter already used");
        }

        $this->connections[$fromCharacter] = $toCharacter;
        $this->connections[$toCharacter] = $fromCharacter;
    }

    public function setCableConnections($assocArray)
    {
        $this->clearAllConnections();

        foreach ($assocArray as $from => $to) {
            $this->addCableConnection($from, $to);
        }
    }

    public function getOutputCharacterForInputCharacter($inputCharacter)
    {
        if (isset($this->connections[$inputCharacter])) {
            $char = $this->connections[$inputCharacter];
        } else {
            $char = $inputCharacter;
        }

        return $char;
    }
}
