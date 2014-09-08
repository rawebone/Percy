<?php

namespace Rawebone\Percy;

interface Validation
{
    /**
     * Returns a string, or an object that can be converted to string,
     * with the details of the invalid value(s) if validation fails.
     * Otherwise should return null if model is valid.
     *
     * @return string|null
     */
    function validate();
}
