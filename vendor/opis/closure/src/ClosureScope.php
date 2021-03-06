<?php
/* ===========================================================================
 * Copyright (c) 2014-2018 The Opis Project
 *
 * Licensed under the MIT License
 * =========================================================================== */

namespace Opis\Closure;


/**
 * Closure scope class
 */
class ClosureScope extends \SplObjectStorage
{
    /**
     * @var integer Number of serializations in current scope
     */
    public $serializations = 0;

    /**
     * @var integer Number of closures that have to be serialized
     */
    public $toserialize = 0;
}