<?php namespace Symfony\Component\VarDumper;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class VarDumper
{
    /**
     * Dump a value with elegance.
     *
     * @param mixed $value
     *
     * @return void
     */
    public function dump($value)
    {
        $dumper = ('cli' === PHP_SAPI) ? new CliDumper() : new HtmlDumper();

        $dumper->dump(with(new VarCloner())->cloneVar($value));
    }
}
