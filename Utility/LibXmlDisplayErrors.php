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

class LibXmlDisplayErrors
{
    public function libxml_display_errors($display_errors = true) {
        $errors = libxml_get_errors();
        $chain_errors = "";

        foreach ($errors as $error) {
            $chain_errors .= preg_replace('/( in\ \/(.*))/', "", strip_tags($this->libxml_display_error($error)))."\n";
            // if ($display_errors) {
            //     trigger_error($this->libxml_display_error($error), E_USER_WARNING);
            // }
        }
        libxml_clear_errors();

        return $chain_errors;
    }

    private function libxml_display_error($error) {
        $return = "";
        switch ($error->level) {
            // case LIBXML_ERR_WARNING:
            //     $return .= "Warning $error->code";
            //     break;
            case LIBXML_ERR_ERROR:
                $return .= "Error $error->code";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "Fatal Error $error->code";
                break;
        }
        if ($error->file) {
            $return .= " in $error->file";
        }
        $return .= " on line $error->line:\n";
        $return .= trim($error->message)."\n";

        return $return;
    }
}
