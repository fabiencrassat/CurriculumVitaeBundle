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

class Tools
{
    /**
     * Determine if two associative arrays are similar
     *
     * Both arrays must have the same indexes with identical values
     * without respect to key ordering
     *
     * @param array $array1
     * @param array $array2
     */
    public function arraysAreSimilar($array1, $array2)
    {
        $difference = array();
        foreach($array1 as $key => $value)  {
            if (is_array($value) && isset($array2[$key]) && is_array($array2[$key])) {
                $new_diff = $this->arraysAreSimilar($value, $array2[$key]);
                if($new_diff != 0) {
                    $difference[$key] = $new_diff;
                }
            } elseif (!array_key_exists($key, $array2)
            || $array2[$key] != $value
            || is_array($value) && (!isset($array2[$key]) || !is_array($array2[$key]))) {
                $difference[$key] = $value;
            }
        }
        if (count($difference) <> 0) {
            return $difference;
        } else {
            return 0;
        }
    }
}
