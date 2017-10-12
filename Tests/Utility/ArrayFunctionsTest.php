<?php

/*
 * This file is part of the FabienCrassat\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabienCrassat\CurriculumVitaeBundle\Tests\Utility;

use FabienCrassat\CurriculumVitaeBundle\Utility\ArrayFunctions;

class ArrayFunctionsTest extends \PHPUnit\Framework\TestCase
{
    public function testArrayValuesRecursive()
    {
        $arrayFunction = new ArrayFunctions();

        $result = $arrayFunction->arrayValuesRecursive(Array(1, 2));
        $this->assertEquals(Array(1, 2), $result);

        $result = $arrayFunction->arrayValuesRecursive(Array(Array(1, 2), Array(3, 4)));
        $this->assertEquals(Array(1, 2, 3, 4), $result);

        $result = $arrayFunction->arrayValuesRecursive(Array(Array(Array(1))));
        $this->assertEquals(Array(1), $result);
    }
}
