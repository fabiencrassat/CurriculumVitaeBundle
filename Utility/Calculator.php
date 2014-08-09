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
    public function getAge($birthday)
    {
        $birthdayDate = date_parse_from_format("Y-m-d", $birthday);
        if ($birthdayDate['error_count'] > 0) {
            throw new InvalidArgumentException("The date (". $birthday .") is bad formatted, expected Y-m-d.");
        }

        // Retreive the date and transform it to integer
        $day = (int) $birthdayDate["day"];
        $month = (int) $birthdayDate["month"];
        $year = (int) $birthdayDate["year"];
        $today = array();

        if(!checkdate($month, $day, $year)) {
            throw new InvalidArgumentException("The date (". $birthday .") is unknown.");
        };

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