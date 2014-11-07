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

class Xml2arrayFunctions {
    private $arXML;
    private $attr;
    private $key;
    private $language;
    private $CVFile;

    /**
     * @param \SimpleXMLElement $CVFile
     */
    public function __construct($CVFile, $language = 'en') {
        $this->language = $language;
        $this->CVFile = $CVFile;
    }

    public function xml2array(\SimpleXMLElement $xml, $depth = 0, $format = TRUE) {
        $depth = $depth + 1;
        $this->arXML = array();
        $this->attr = array();

        // Extraction of the node
        $this->key = trim($xml->getName());
        $value = trim((string) $xml);

        // Specific Attribute: do nothing when it is not the good language
        if ($xml->attributes()->lang) {
            if ($xml->attributes()->lang <> $this->language) {
                return NULL;
            }
            unset($xml->attributes()->lang);
        }
        // Specific Attributes
        $this->key   = $this->setSpecificAttributeKeyWithGivenId($xml, $this->key);
        $value = $this->setSpecificAttributeAge($xml, $value);
        $this->arXML = $this->retrieveSpecificAttributeCrossRef($xml, $this->arXML, $this->key);
        // Standard Attributes
        $this->setStandardAttributes($xml);
        // Specific Key
        $value = $this->setValueForSpecificKeys($this->key, $value, $format);

        $this->setValue($value);
        $this->setAttribute();
        $this->arXML = $this->setChildren($xml, $depth, $this->key, $this->arXML);

        return $this->arXML;
    }

    /**
     * @param string $value
     */
    private function setValue($value) {
        if ($value <> '') {
            $this->arXML = array_merge($this->arXML, array($this->key => $value));
        }
    }

    private function setAttribute() {
        if (count($this->attr) > 0) {
            $this->arXML = array_merge($this->arXML, array($this->key => $this->attr));
        }
    }

    /**
     * @param integer $depth
     * @param string $key
     */
    private function setChildren(\SimpleXMLElement $xml, $depth, $key, array $arXML) {
        if ($xml->children()->count() > 0) {
            foreach($xml->children() as $childKey => $childValue) {
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

    private function setStandardAttributes(\SimpleXMLElement $xml) {
        // Standard Attributes (without Specific thanks to unset())
        foreach($xml->attributes() as $attributeKey => $attributeValue) {
            $this->attr[$attributeKey] = trim($attributeValue);
        }
    }

    /**
     * @param string $key
     */
    private function setSpecificAttributeKeyWithGivenId(\SimpleXMLElement $xml, $key) {
        // Specific Attribute: change the key with the given id
        if ($xml->attributes()->id) {
            $key = (string) $xml->attributes()->id;
            unset($xml->attributes()->id);
        }
        return $key;
    }

    /**
     * @param string $value
     */
    private function setSpecificAttributeAge(\SimpleXMLElement $xml, $value) {
        // Specific Attribute: Retreive the age
        if ($xml->attributes()->getAge) {
            $CVCrossRef = $this->CVFile->xpath(trim($xml->attributes()->getAge));
            $cr = $this->xml2array($CVCrossRef[0], NULL, FALSE);
            $cr = implode("", $cr);
            $AgeCalculator = new AgeCalculator((string) $cr);
            $value = $AgeCalculator->age();
            unset($xml->attributes()->getAge);
        }
        return $value;
    }

    /**
     * @param string $key
     */
    private function retrieveSpecificAttributeCrossRef(\SimpleXMLElement $xml, array $arXML, $key) {
        // Specific Attribute: Retrieve the given crossref
        if ($xml->attributes()->crossref) {
            $CVCrossRef = $this->CVFile->xpath(trim($xml->attributes()->crossref));
            $cr = $this->xml2array($CVCrossRef[0]);
            $arXML = array_merge($arXML, array($key => $cr));
            unset($xml->attributes()->crossref);
        }
        return $arXML;
    }

    /**
     * @param string $key
     * @param boolean $format
     *
     * @return array|string $value
     */
    private function setValueForSpecificKeys($key, $value, $format) {
        if ($key == 'birthday') {
            return $this->setValueForBirthdayKey($value, $format);
        }
        elseif ($key == "item") {
            return $this->setValueForItemKey($value);
        }
        else {
            return $value;
        }
    }

    /**
     * Specific Key: Format to french date format
     *
     * @param boolean $format
     *
     * @return string
     */
    private function setValueForBirthdayKey($value, $format) {
        if ($format) {
            setlocale(LC_TIME, array('fra_fra', 'fr', 'fr_FR', 'fr_FR.UTF8'));
            $value = strftime('%d %B %Y', strtotime(date($value)));
        }

        return $value;
    }

    /**
     * Specific Key: convert to apply array_merge()
     *
     * @return array
     */
    private function setValueForItemKey($value) {
        return array($value);
    }
}
