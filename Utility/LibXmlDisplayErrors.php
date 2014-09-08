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
    private $errors;
    private $chainErrors;
    private $error;
    private $content;

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

        return $this->chainErrors;
    }

    private function libXmlDisplayError() {
        $this->setEmptyContent();
        $this->addErrorLevelContent();
        $this->addFileContent();
        $this->addLineAndColumnContent();
        $this->addErrorMessageContent();

        return preg_replace('/( in\ \/(.*))/', "", strip_tags($this->getContent()))."\n";
    }

    private function addErrorLevelContent()
    {
        switch ($this->error->level) {
            case LIBXML_ERR_WARNING:
                $this->addInContent("Warning ".($this->error->code));
                break;
            case LIBXML_ERR_ERROR:
                $this->addInContent("Error ".($this->error->code));
                break;
            case LIBXML_ERR_FATAL:
                $this->addInContent("Fatal Error ".($this->error->code));
                break;
        }
    }

    private function addFileContent()
    {
        if ($this->error->file) {
            $this->addInContent(" in ".($this->error->file));
        }
    }

    private function addLineAndColumnContent()
    {
        $this->addInContent(" on line ".($this->error->line)." column ".($this->error->column).":\n");
    }

    private function addErrorMessageContent()
    {
        $this->addInContent(trim($this->error->message)."\n");
    }

    /**
     * @param string $content
     */
    private function addInContent($content)
    {
        $this->content .= $content;
    }

    private function setEmptyContent()
    {
        $this->content = "";
    }

    private function getContent()
    {
        return $this->content;
    }
}
