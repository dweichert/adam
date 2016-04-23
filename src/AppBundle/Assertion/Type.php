<?php
/**
 * This file is part of adam.
 *
 * (c) David Weichert <info@davidweichert.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Assertion;


class Type
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function isIntegerish($value)
    {
        if (ctype_digit((string)$value))
        {
            return true;
        }

        return false;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isIntegerishAndNotZero($value)
    {
        if (!$this->isIntegerish($value))
        {
            return false;
        }
        if ($value == 0)
        {
            return false;
        }

        return true;
    }
}
