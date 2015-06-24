<?php namespace Symfony\Component\VarDumper\Caster;

use Symfony\Component\VarDumper\Cloner\Stub;

/**
 * Casts a caster's Stub.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class StubCaster
{
    public static function castStub(Stub $c, array $a, Stub $stub, $isNested)
    {
        if ($isNested) {
            $stub->type = $c->type;
            $stub->class = $c->class;
            $stub->value = $c->value;
            $stub->handle = $c->handle;
            $stub->cut = $c->cut;

            return array();
        }
    }

    public static function castCutArray(CutArrayStub $c, array $a, Stub $stub, $isNested)
    {
        return $isNested ? $c->preservedSubset : $a;
    }

    public static function cutInternals($obj, array $a, Stub $stub, $isNested)
    {
        if ($isNested) {
            $stub->cut += count($a);

            return array();
        }

        return $a;
    }
}
