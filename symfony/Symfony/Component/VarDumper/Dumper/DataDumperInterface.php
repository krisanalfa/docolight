<?php namespace Symfony\Component\VarDumper\Dumper;

use Symfony\Component\VarDumper\Cloner\Data;

/**
 * DataDumperInterface for dumping Data objects.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
interface DataDumperInterface
{
    /**
     * Dumps a Data object.
     *
     * @param Data $data A Data object.
     */
    public function dump(Data $data);
}
