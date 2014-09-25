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

}
