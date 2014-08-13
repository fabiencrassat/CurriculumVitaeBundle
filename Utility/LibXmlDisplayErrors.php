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

class LibXmlDisplayErrors
{
    private $errors;
    private $chainErrors;
    private $error;

    public function __construct()
    {
        $this->errors = libxml_get_errors();
        $this->chainErrors = "";
    }

    public function libXmlDisplayErrors() {
        foreach ($this->errors as $error) {
            $this->error = $error;
            $this->chainErrors .= $this->libXmlDisplayError();
        }
        libxml_clear_errors();

        throw new InvalidArgumentException($this->chainErrors);
    }

    private function libXmlDisplayError() {
        $return = "";
        switch ($this->error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "Warning ".($this->error->code);
                break;
            case LIBXML_ERR_ERROR:
                $return .= "Error ".($this->error->code);
                break;
            case LIBXML_ERR_FATAL:
                $return .= "Fatal Error ".($this->error->code);
                break;
        }
        if ($this->error->file) {
            $return .= " in ".($this->error->file);
        }
        $return .= " on line ".($this->error->line)." column ".($this->error->column).":\n";
        $return .= trim($this->error->message)."\n";
        $return = preg_replace('/( in\ \/(.*))/', "", strip_tags($return))."\n";

        return $return;
    }
}