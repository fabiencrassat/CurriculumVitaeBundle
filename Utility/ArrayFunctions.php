<?php

/*
 * This file is part of the FabienCrassat\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabienCrassat\CurriculumVitaeBundle\Utility;

class ArrayFunctions
{
    /**
     * @param array $array
     */
    public function arrayValuesRecursive($array)
    {
        $result = [];
        foreach ($array as $value) {
            $result = $this->arrayValuesMerge($result, $value);
        }
        return $result;
    }

    private function arrayValuesMerge($array, $value)
    {
        if (is_array($value)) {
            return array_merge($array, $this->arrayValuesRecursive($value));
        }

        return array_merge($array, [$value]);
    }
}
