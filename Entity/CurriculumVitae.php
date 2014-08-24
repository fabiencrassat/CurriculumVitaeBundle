<?php

/*
 * This file is part of the FabienCrassat\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabienCrassat\CurriculumVitaeBundle\Entity;

use FabienCrassat\CurriculumVitaeBundle\Utility\AgeCalculator;
use FabienCrassat\CurriculumVitaeBundle\Utility\LibXmlDisplayErrors;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

class CurriculumVitae
{
    private $pathToFile;
    private $lang;
    private $CV;
    private $file;
    // xml2array variables

    /**
     * @param string $pathToFile
     */
    public function __construct($pathToFile, $lang = 'en') {
        $this->pathToFile = $pathToFile;
        $this->setFileName();
        $this->lang = $lang;
        $this->CV = $this->getXmlCurriculumVitae();
    }

    private function setFileName() {
        $data = explode("/", $this->pathToFile);
        $data = $data[count($data) - 1];
        $data = explode(".", $data);;
        $this->file = $data[0];
    }

    private function getXmlCurriculumVitae() {
        if (is_null($this->pathToFile) || !is_file($this->pathToFile)) {
            throw new InvalidArgumentException("The path " . $this->pathToFile . " is not a valid path to file.");
        }
        $this->validateXmlCurriculumVitae();

        return simplexml_load_file($this->pathToFile);
    }

    private function validateXmlCurriculumVitae() {
        // Activer "user error handling"
        libxml_use_internal_errors(TRUE);

        // Instanciation d’un DOMDocument
        $dom = new \DOMDocument("1.0");

        // Charge du XML depuis un fichier
        $dom->load($this->pathToFile);

        // Validation du document XML
        $reflClass = new \ReflectionClass(get_class($this));
        $xsdFile = dirname($reflClass->getFileName()).'/validator.xsd';
        $validate = $dom->schemaValidate($xsdFile);
        if (!$validate) {
            $libxmlDisplayErrors = new LibXmlDisplayErrors;
            throw new InvalidArgumentException($libxmlDisplayErrors->libXmlDisplayErrors());;
        }
        
        return $validate;
    }

    public function getDropDownLanguages() {
        $return = $this->getXMLValue($this->CV->langs);
        if(!$return) {
            $return = array($this->lang => $this->lang);
        }

        return $return;
    }

    public function getAnchors() {
        $anchorsAttribute = $this->CV->xpath("curriculumVitae/*[attribute::anchor]");
        
        $anchors = array();
        foreach ($anchorsAttribute as $anchorsKey => $anchorsValue) {
            $anchor = (string) $anchorsValue['anchor'];
            $title = $anchorsValue->xpath("anchorTitle[@lang='" . $this->lang . "']");
            if (count($title) == 0) {
                $title = $anchorsValue->xpath("anchorTitle");
            }
            $anchors[$anchor] = array(
                'href'  => $anchor,
                'title' => (string) $title[0],
            );
        };

        return $anchors;
    }

    public function getHumanFileName() {
        $myName = $this->getMyName();
        $myCurrentJob = $this->getMyCurrentJob();
        if (NULL != $myName) {
            if (NULL != $myCurrentJob) {
                return $myName.' - '.$myCurrentJob;
            } else {
                return $myName;
            }
        } else {
            return $this->file;
        }        
    }

    private function getMyName() {
        $identity = $this->getIdentity();
        return $identity['myself']['name'];
    }

    private function getMyCurrentJob() {
        $lookingFor = $this->getLookingFor();
        if (isset($lookingFor['experience']['job'])) {
            return $lookingFor['experience']['job'];
        } elseif (isset($lookingFor['experience'])) {
            return $lookingFor['experience'];
        } else {
            return NULL;
        }
    }

    public function getIdentity() {
        return $this->getXMLValue($this->CV->curriculumVitae->identity->items);
    }

    public function getFollowMe() {
        return $this->getXMLValue($this->CV->curriculumVitae->followMe->items);
    }

    public function getLookingFor() {
        return $this->getXMLValue($this->CV->curriculumVitae->lookingFor);
    }

    public function getExperiences() {
        return $this->getXMLValue($this->CV->curriculumVitae->experiences->items);
    }

    public function getSkills() {
        return $this->getXMLValue($this->CV->curriculumVitae->skills->items);
    }

    public function getEducations() {
        return $this->getXMLValue($this->CV->curriculumVitae->educations->items);
    }

    public function getLanguageSkills() {
        return $this->getXMLValue($this->CV->curriculumVitae->languageSkills->items);
    }

    public function getMiscellaneous() {
        return $this->getXMLValue($this->CV->curriculumVitae->miscellaneous->items);
    }

    private function getXMLValue($xml) {
        if (!$xml) {
            return NULL;
        } else {
            return $this->xml2array($xml);
        }
    }

    private function xml2array(\SimpleXMLElement $xml, $depth = 0, $format = TRUE) {
        $depth = $depth + 1;

        // Extraction of the node
        $key = trim($xml->getName());
        $value = trim((string) $xml);
        $attributes = $xml->attributes();
        $children = $xml->children();

        $arXML = array();
        $attr = array();

        // Specific Attribute: do nothing when it is not the good language
        if ($attributes->lang) {
            if ($attributes->lang <> $this->lang) {
                return NULL;
            }
            unset($attributes->lang);
        }
        // Specific Attribute: change the key with the given id
        if ($attributes->id) {
            $key = (string) $attributes->id;
            unset($attributes->id);
        }
        // Specific Attribute: Retreive the given crossref
        if ($attributes->crossref) {
            $CVCrossRef = $this->CV->xpath(trim($attributes->crossref));
            $cr = $this->xml2array($CVCrossRef[0]);
            $arXML = array_merge($arXML, array($key => $cr));
            unset($attributes->crossref);
        }
        // Specific Attribute: Retreive the age
        if ($attributes->getAge) {
            $CVCrossRef = $this->CV->xpath(trim($attributes->getAge));
            $cr = $this->xml2array($CVCrossRef[0], NULL, FALSE);
            $cr = implode("", $cr);
            $AgeCalculator = new AgeCalculator((string) $cr);
            $value = $AgeCalculator->age();
            unset($attributes->getAge);
        }
        // Standard Attributes (without Specific thanks to unset())
        foreach($attributes as $attributeKey => $attributeValue) {
            $attr[$attributeKey] = trim($attributeValue);
        }

        // Specific Key
        $value = $this->setValueForSpecificKeys($key, $value, $format);

        // Value
        if ($value <> '') {
            $arXML = array_merge($arXML, array($key => $value));
        }
        // Attribute
        if (count($attr) > 0) {
            $arXML = array_merge($arXML, array($key => $attr));
        }
        // Children
        if (Count($children) > 0) {
            foreach($children as $childKey => $childValue) {
                $child = $this->xml2array($childValue, $depth);
                if ($depth > 1 && $child) {
                    $arXML = array_merge_recursive($arXML, array($key => $child));
                } elseif ($child) {
                    $arXML = array_merge_recursive($arXML, $child);
                }
            }
        }
        
        return $arXML;
    }

    /**
     * @param string $key
     * @param boolean $format
     */
    private function setValueForSpecificKeys($key, $value, $format) {
        // Specific Key: Format to french date format
        if ($key == 'birthday') {
            if ($format) {
                setlocale(LC_TIME, array('fra_fra', 'fr', 'fr_FR', 'fr_FR.UTF8'));
                $value = strftime('%d %B %Y', strtotime(date($value)));
            }
        }
        // Specific Key: convert to apply array_merge()
        if ($key == "item") {
            $value = array($value);
        }
        return $value;
    }
}