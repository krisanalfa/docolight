<?php

namespace Docoflow\Contracts;

/**
 * Trait cannot create a constant, so, we should move this to an interface. Implements this contract to a class, then trait can access this constant.
 *
 * @author Krisan Alfa Timur <krisanalfa@gmail.com>
 */
interface ValidationStatus
{
    const UNPROCESSED = 0;
    const APPROVED = 1;
    const PARTIAL = 2;
    const REJECTED = 3;
    const INVALID = 4;
}
