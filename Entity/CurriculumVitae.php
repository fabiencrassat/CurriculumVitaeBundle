<?php

namespace Nimbusletruand\CurriculumVitaeBundle\Entity;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;

class CurriculumVitae
{
    public $CV;
    private $Lang;
    private $nMaxRecursiveDepth = 8; 

    public function __construct($pathToFile, $Lang = 'en')
    {
        $this->Lang = $Lang;
        $this->CV = $this->getXmlCurriculumVitae($pathToFile);
    }

    public function getDropDownLanguages()
    {
        return $this->xml2array($this->CV->lang);
    }

    public function getAnchors()
    {
        $anchorsAttribute = $this->CV->xpath("CurriculumVitae/*[attribute::anchor]");
        
        $anchors = array();
        $i = 0;
        foreach ($anchorsAttribute as $key => $value) {
            $anchor = (string) $value['anchor'];
            $title = $value->xpath("AnchorTitle[@lang='" . $this->Lang . "']");
            $anchors[$anchor] = array(
                'href'  => $anchor,
                'title' => (string) $title[0],
            );
        };

        return $anchors;
    }

    public function getIdentity()
    {
        return $this->xml2array($this->CV->CurriculumVitae->identity->items);
    }

    public function getFollowMe()
    {
        return $this->xml2array($this->CV->CurriculumVitae->followMe->items);
    }

    public function getLookingFor()
    {
        return $this->xml2array($this->CV->CurriculumVitae->lookingFor);
    }

    public function getExperiences()
    {
        return $this->xml2array($this->CV->CurriculumVitae->experiences->items);
    }

    public function getSkills()
    {
        return $this->xml2array($this->CV->CurriculumVitae->skills->items);
    }

    public function getEducations()
    {
        return $this->xml2array($this->CV->CurriculumVitae->educations->items);
    }

    public function getLanguageSkills()
    {
        return $this->xml2array($this->CV->CurriculumVitae->languageSkills->items);
    }

    public function getMiscellaneous()
    {
        return $this->xml2array($this->CV->CurriculumVitae->miscellaneous->items);
    }

        public function getSociety()
    {
        return $this->xml2array($this->CV->Society);
    }

    private function xml2array($xml, $depth = 0, $format = true) {
        if ($depth >= $this->nMaxRecursiveDepth) {
            throw new InvalidArgumentException("The recursive funtion xml2array (depth=" . $depth . ") is too high.");
        } else {
            $depth = $depth + 1;
        }

        // Extraction of the node
        $key = trim($xml->getName());
        $value = trim((string)$xml);
        $attributes = $xml->attributes();
        $children = $xml->children();

        $arXML = array();
        $bContinue = true;
        $attr = array();

        if ($depth == 1 && $key == "") {
            throw new InvalidArgumentException("The curriculum vitae xml file is not valid");
        } else {
            // Specific Attributes
            foreach($attributes as $attributeKey => $attributeValue) {
                $valuetemp = trim($attributeValue);
                if ($attributeKey == "lang") {
                    if($valuetemp <> $this->Lang) {
                        $bContinue = false;
                        break;
                    }
                } elseif ($attributeKey == "crossref") {
                    $CVCrossRef = $this->CV;
                    $tabtemp = explode("/", $valuetemp);
                    foreach ($tabtemp as $val) {
                        $CVCrossRef = $CVCrossRef->{ $val };
                    }
                    $cr = $this->xml2array($CVCrossRef);
                    // if (count($cr) == 1) {
                    //     $cr = implode("", $cr);
                    // }
                    $arXML = array_merge($arXML, array($key => $cr));
                    break;
                } else {
                    $attr[$attributeKey] = $valuetemp;
                }
            }
        }

        if($bContinue) {

            if ($key == 'BirthDay') {
                if ($format) {
                    setlocale(LC_TIME, array('fra_fra', 'fr', 'fr_FR', 'fr_FR.UTF8'));
                    $value = strftime('%d %B %Y', strtotime(date($value)));
                }
                $attr = array();
            } elseif ($key == 'Age') {
                $CVCrossRef = $this->CV;
                $tabtemp = explode("/", $valuetemp);
                foreach ($tabtemp as $val) {
                    $CVCrossRef = $CVCrossRef->{ $val };
                }
                $cr = $this->xml2array($CVCrossRef, NULL, FALSE);
                if (count($cr) == 1) {
                    $cr = implode("", $cr);
                }
                $CVCrossRef = (array) $CVCrossRef;
                $dateFormat = $CVCrossRef['@attributes']['format'];

                $attr = array();
                $value = $this->getAge((string) $cr, $dateFormat);
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
                $t = array();
                foreach($children as $childKey => $childValue) {
                    $child = $this->xml2array($childValue, $depth);
                    if ($child) {
                        if ($depth > 1) {
                            $arXML = array_merge_recursive($arXML, array($key => $child));
                        } else {
                            $arXML = array_merge_recursive($arXML, $child);
                        }
                    }
                }
            }
            
            return $arXML;
        } else {
            return null;
        }
    }

    private function getXmlCurriculumVitae($pathToFile)
    {
        if (is_null($pathToFile) || !is_file($pathToFile)) {
            throw new InvalidArgumentException("The path " . $pathToFile . " is not a valid path to file.");
        }
        return simplexml_load_file($pathToFile);
    }

    private function getAge($birthday, $dateFormat)
    {
        if($dateFormat <> "mm/dd/yy") {
            throw new InvalidArgumentException("The format " . $dateFormat . " is not defined.");
        };
        list($month, $day, $year) = preg_split('[/]', $birthday);
        $today['day'] = date('j');
        $today['month'] = date('n');
        $today['year'] = date('Y');
        $age = $today['year'] - $year;
        if ($today['month'] <= $month) {
            if ($month == $today['month']) {
                if ($day > $today['day'])
                    $age--;
            }
            else {
                $age--;
            }
        };

        return $age;
    }

}

?>