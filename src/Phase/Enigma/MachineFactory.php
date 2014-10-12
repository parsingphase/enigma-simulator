<?php
/**
 * Created by PhpStorm.
 * User: parsingphase
 * Date: 12/10/14
 * Time: 15:15
 */

namespace Phase\Enigma;


class MachineFactory
{

    // List of models drawn from http://www.cryptomuseum.com/crypto/enigma/tree.htm

    //potentially supported models
    const MODEL_D = 'D'; // 1926 commercial model; need settable reflector
    const MODEL_K = 'K'; //1927 commercial model, adapted for Railway use
    const MODEL_RAILWAY = 'K'; // alias

    const MODEL_I = 'I'; // 1932 Service Enigma

    const MODEL_T = 'T'; // Japanese Tirpitz model
    const MODEL_TIRPITZ = 'T'; // alias

    // M1 - M3 are functionally identical
    const MODEL_M1 = 'M'; // 1934 Service model
    const MODEL_M2 = 'M'; // 1938 Service model
    const MODEL_M3 = 'M'; // 1940 Service model
    const MODEL_M123 = 'M'; // alias
    const MODEL_M4 = 'M4'; // 1941 U-boat model ('SHARK' key)


    // unsupported models
    const MODEL_A = 'A'; // Printing model, cogs, no reflector
    const MODEL_B = 'B'; // Printing model, insufficient info
    const MODEL_C = 'C'; // more info needed
    const MODEL_H = 'H'; // 8-wheel model, insufficient info
    const MODEL_2 = 'H'; // Alias for Model H (Enigma II)
    const MODEL_ZAHLWERK = 'ZA'; //Cog turnover not supported
    const MODEL_Z = 'Z'; //Numeric model, insufficient info
    const MODEL_ZIFFER = 'Z'; //Alias for model Z (numeric, "ziffer")

    protected $supportedModels = [self::MODEL_M123, self::MODEL_M4];

    /**
     * @return array
     */
    public function getSupportedModels()
    {
        return $this->supportedModels;
    }

    public function buildMachineInstance($modelId)
    {
        $entryDiscFactory = new EntryDiscFactory();

        $machine = new Machine();
        switch ($modelId) {
            case self::MODEL_M123:
                $machine->setPlugboard(new Plugboard());
                $machine->setEntryDisc(
                    $entryDiscFactory->buildEntryDiscInstance(EntryDiscFactory::ENTRYDISC_ALPHABET)
                );
                $machine->setPawls([new Pawl(), new Pawl(), new Pawl()]);
                $machine->setRotorSlots([new RotorSlot(), new RotorSlot(), new RotorSlot()]);
                // Reflector must be chosen by user
                break;

            case self::MODEL_M4:
                $machine->setPlugboard(new Plugboard());
                $machine->setEntryDisc(
                    $entryDiscFactory->buildEntryDiscInstance(EntryDiscFactory::ENTRYDISC_ALPHABET)
                );
                $machine->setPawls([new Pawl(), new Pawl(), new Pawl()]);
                $machine->setRotorSlots([new RotorSlot(), new RotorSlot(), new RotorSlot(), new RotorSlot()]);
                // Reflector must be chosen by user
                break;

            default:
                throw new \InvalidArgumentException("Model '$modelId' not supported");
        }

        return $machine;
    }
}
