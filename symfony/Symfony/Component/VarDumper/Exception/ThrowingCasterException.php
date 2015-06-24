<?php namespace Symfony\Component\VarDumper\Exception;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class ThrowingCasterException extends \Exception
{
    /**
     * @param callable   $caster The failing caster
     * @param \Exception $prev   The exception thrown from the caster
     */
    public function __construct($caster, \Exception $prev)
    {
        parent::__construct('Unexpected '.get_class($prev).' thrown from a caster: '.$prev->getMessage(), 0, $prev);
    }
}
