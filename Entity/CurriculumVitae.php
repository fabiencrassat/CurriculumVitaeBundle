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

use FabienCrassat\CurriculumVitaeBundle\Utility\Calculator;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

class CurriculumVitae
{
    private $CV;
    private $Lang;
    private $nMaxRecursiveDepth = 8; 

    /**
     * @param string $pathToFile
     */
    public function __construct($pathToFile, $Lang = 'en')
    {
        $this->Lang = $Lang;
        $this->CV = $this->getXmlCurriculumVitae($pathToFile);
    }

    public function getDropDownLanguages()
    {
        $return = $this->xml2array($this->CV->langs);
        if(Count($return) == 0) {
            $return = array($this->Lang => $this->Lang);
        }

        return $return;
    }

    public function getAnchors()
    {
        $anchorsAttribute = $this->CV->xpath("curriculumVitae/*[attribute::anchor]");
        
        $anchors = array();
        foreach ($anchorsAttribute as $key => $value) {
            $anchor = (string) $value['anchor'];
            $title = $value->xpath("anchorTitle[@lang='" . $this->Lang . "']");
            $anchors[$anchor] = array(
                'href'  => $anchor,
                'title' => (string) $title[0],
            );
        };

        return $anchors;
    }

    public function getIdentity()
    {
        return $this->getXMLValue($this->CV->curriculumVitae->identity->items);
    }

    public function getFollowMe()
    {
        return $this->getXMLValue($this->CV->curriculumVitae->followMe->items);
    }

    public function getLookingFor()
    {
        return $this->getXMLValue($this->CV->curriculumVitae->lookingFor);
    }

    public function getExperiences()
    {
        return $this->getXMLValue($this->CV->curriculumVitae->experiences->items);
    }

    public function getSkills()
    {
        return $this->getXMLValue($this->CV->curriculumVitae->skills->items);
    }

    public function getEducations()
    {
        return $this->getXMLValue($this->CV->curriculumVitae->educations->items);
    }

    public function getLanguageSkills()
    {
        return $this->getXMLValue($this->CV->curriculumVitae->languageSkills->items);
    }

    public function getMiscellaneous()
    {
        return $this->getXMLValue($this->CV->curriculumVitae->miscellaneous->items);
    }

    public function getSociety()
    {
        return $this->getXMLValue($this->CV->societies);
    }

    private function getXMLValue($xml)
    {
        if (!$xml) {
            return NULL;
        } else {
            return $this->xml2array($xml);
        }
    }

    private function xml2array(\SimpleXMLElement $xml, $depth = 0, $format = TRUE) {
        $calculator = new Calculator();

        if ($depth >= $this->nMaxRecursiveDepth) {
            throw new InvalidArgumentException("The recursive funtion xml2array (depth=" . $depth . ") is too high.");
        } else {
            $depth = $depth + 1;
        }

        // Extraction of the node
        $key = trim($xml->getName());
        $value = trim((string) $xml);
        $attributes = $xml->attributes();
        $children = $xml->children();

        $arXML = array();
        $bContinue = TRUE;
        $attr = array();

        if ($depth == 1 && $key == "") {
            throw new InvalidArgumentException("The curriculum vitae xml file is not valid");
        } else {
            // Specific Attributes
            foreach($attributes as $attributeKey => $attributeValue) {
                $valuetemp = trim($attributeValue);
                if ($attributeKey == "lang") {
                    if($valuetemp <> $this->Lang) {
                        $bContinue = FALSE;
                        break;
                    }
                } elseif ($attributeKey == "crossref") {
                    $CVCrossRef = $this->CV->xpath($valuetemp);
                    $cr = $this->xml2array($CVCrossRef[0]);
                    $arXML = array_merge($arXML, array($key => $cr));
                    break;
                } elseif ($attributeKey == "id") {
                    $key = (string) $attributeValue;
                } else {
                    $attr[$attributeKey] = $valuetemp;
                }
            }
        }

        if($bContinue) {

            if ($key == 'birthday') {
                if ($format) {
                    setlocale(LC_TIME, array('fra_fra', 'fr', 'fr_FR', 'fr_FR.UTF8'));
                    $value = strftime('%d %B %Y', strtotime(date($value)));
                }
                $attr = array();
            } elseif ($key == 'age' && array_key_exists("getAge", $attr)) {
                $CVCrossRef = $this->CV;
                $tabtemp = explode("/", $attr["getAge"]);
                foreach ($tabtemp as $val) {
                    $CVCrossRef = $CVCrossRef->{ $val };
                }
                $cr = $this->xml2array($CVCrossRef, NULL, FALSE);
                if (count($cr) == 1) {
                    $cr = implode("", $cr);
                };

                $attr = array();
                $value = $calculator->getAge((string) $cr);
            }

            // Value
            if ($value <> '') {
                if ($key == "item") {
                    $arXML = array_merge($arXML, array($key => array($value)));
                } else {
                    $arXML = array_merge($arXML, array($key => $value));
                }
            }
            // Attribute
            if (count($attr) > 0) {
                $arXML = array_merge($arXML, array($key => $attr));
            }
            // Children
            if (Count($children) > 0) {
                foreach($children as $childKey => $childValue) {
                    $child = $this->xml2array($childValue, $depth);
                    if ($depth > 1) {
                        $arXML = array_merge_recursive($arXML, array($key => $child));
                    } elseif ($child) {
                        $arXML = array_merge_recursive($arXML, $child);
                    }
                }
            }
            
            return $arXML;
        } else {
            return NULL;
        }
    }

    /**
     * @param string $pathToFile
     */
    private function getXmlCurriculumVitae($pathToFile)
    {
        if (is_null($pathToFile) || !is_file($pathToFile)) {
            throw new InvalidArgumentException("The path " . $pathToFile . " is not a valid path to file.");
        }
        return simplexml_load_file($pathToFile);
    }

}
