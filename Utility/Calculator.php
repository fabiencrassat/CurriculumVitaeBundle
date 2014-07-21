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

use Symfony\Component\Serializer\Exception\InvalidArgumentException;

class Calculator
{
    /**
     * @param string $birthday
     */
    public function getAge($birthday, $dateFormat = "mm/dd/yy")
    {
        if($dateFormat <> "mm/dd/yy") {
            throw new InvalidArgumentException("The format " . $dateFormat . " is not defined.");
        };

        // Retreive the date and transform it to integer
        list($month, $day, $year) = preg_split('[/]', $birthday);
        $day = (int) $day;
        $month = (int) $month;
        $year = (int) $year;
        $today = array();

        // Retreive today and transform it to integer
        $today['day'] = (int) date('j');
        $today['month'] = (int) date('n');
        $today['year'] = (int) date('Y');


        // The calculator of the age
        $age = $today['year'] - $year;
        if ($today['month'] <= $month) {
            if ($month == $today['month']) {
                if ($day > $today['day']) {
                    $age--;
                }
            }
            else {
                $age--;
            }
        };

        return $age;
    }
}