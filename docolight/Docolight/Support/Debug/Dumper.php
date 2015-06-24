<?php

namespace Docolight\Support\Debug;

use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;

/**
 * A good dumper for you :).
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class Dumper
{
    /**
     * Dump a value with elegance.
     *
     * @param mixed $value
     */
    public function dump($value)
    {
        $dumper = 'cli' === PHP_SAPI ? new CliDumper() : new HtmlDumper();

        $dumper->dump((new VarCloner())->cloneVar($value));
    }
}
