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

    public function addCableConnection($from, $to)
    {
        if (!(preg_match('/^[A-Z]$/', $from) && preg_match('/^[A-Z]$/', $to))) {
            throw new \InvalidArgumentException("Invalid pair $from-$to");
        }

        if (isset($this->connections[$from])) {
            throw new \InvalidArgumentException("Socket $from already used");
        }

        if (isset($this->connections[$to])) {
            throw new \InvalidArgumentException("Socket $to already used");
        }

        $this->connections[$from] = $to;
        $this->connections[$to] = $from;
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
