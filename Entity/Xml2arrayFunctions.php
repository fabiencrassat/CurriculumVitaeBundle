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
    private $language;
    private $file;

    /**
     * @param \SimpleXMLElement $file
     * @param string $language
     */
    public function __construct($file, $language = 'en') {
        $this->language = $language;
        $this->file = $file;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param integer $depth
     * @param boolean $format
     *
     * @return null|array
     */
    public function xml2array(\SimpleXMLElement $xml, $depth = 0, $format = TRUE) {
        $depth  = $depth + 1;
        $result = array();

        // Extraction of the node
        $key   = trim($xml->getName());
        $value = trim((string) $xml);

        // Specific Attribute: do nothing when it is not the good language
        if ($xml->attributes()->lang) {
            if ($xml->attributes()->lang <> $this->language) {
                return NULL;
            }
            unset($xml->attributes()->lang);
        }

        // Specific Attributes changing the xml
        $key    = $this->setSpecificAttributeKeyWithGivenId($xml, $key);
        $value  = $this->setSpecificAttributeAge($xml, $depth, $value);
        $result = $this->retrieveSpecificAttributeCrossRef($xml, $depth, $result, $key);
        // Standard Attributes
        $result = $this->setStandardAttributes($xml, $result, $key);
        // Specific Key
        $value = $this->setValueForSpecificKeys($key, $value, $format);

        $result = $this->setValue($result, $key, $value);
        $result = $this->setChildren($xml, $depth, $key, $result);

        return $result;
    }

    /**
     * @param array $array
     * @param string $key
     * @param array|string $value
     *
     * @return array
     */
    private function setValue($array, $key, $value) {
        if ($value <> '') {
            $array = array_merge($array, array($key => $value));
        }
        return $array;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param integer $depth
     * @param string $key
     * @param array $arXML
     *
     * @return array
     */
    private function setChildren(\SimpleXMLElement $xml, $depth, $key, array $arXML) {
        if ($xml->children()->count() > 0) {
            foreach($xml->children() as $childValue) {
                $child = $this->xml2array($childValue, $depth);
                if ($depth > 1 && ! empty($child)) {
                    $arXML = array_merge_recursive($arXML, array($key => $child));
                } elseif (! empty($child)) {
                    $arXML = array_merge_recursive($arXML, $child);
                }
            }
        }
        return $arXML;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param array $result
     * @param string $key
     *
     * @return array
     */
    private function setStandardAttributes(\SimpleXMLElement $xml, $result, $key) {
        // Standard Attributes (without Specific thanks to unset())
        $attributes = array();
        foreach($xml->attributes() as $attributeKey => $attributeValue) {
            if ($attributeKey <> 'id'
            && $attributeKey <> 'ref'
            && $attributeKey <> 'lang'
            && $attributeKey <> 'crossref') {
                $attributes[$attributeKey] = trim($attributeValue);
            }
        }
        if (count($attributes) > 0) {
            $result = array_merge_recursive($result, array($key => $attributes));
        }
        return $result;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param string $key
     *
     * @return string
     */
    private function setSpecificAttributeKeyWithGivenId(\SimpleXMLElement $xml, $key) {
        // Specific Attribute: change the key with the given id
        if ($xml->attributes()->id) {
            $key = (string) $xml->attributes()->id;
        }
        return $key;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param integer $depth
     * @param string $value
     *
     * @return string
     */
    private function setSpecificAttributeAge(\SimpleXMLElement $xml, $depth, $value) {
        // Specific Attribute: Retreive the age
        if ($xml->attributes()->getAge) {
            $crossref = $this->file->xpath(trim($xml->attributes()->getAge));
            $birthday = $this->xml2array(clone $crossref[0], $depth, FALSE);
            $birthday = implode("", $birthday);

            $ageCalculator = new AgeCalculator((string) $birthday);

            $value = (string) $ageCalculator->age();
        }
        return $value;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param integer $depth
     * @param array $arXML
     * @param string $key
     *
     * @return array
     */
    private function retrieveSpecificAttributeCrossRef(\SimpleXMLElement $xml, $depth, array $arXML, $key) {
        // Specific Attribute: Retrieve the given crossref
        if ($xml->attributes()->crossref) {
            $crossref = $this->file->xpath(trim($xml->attributes()->crossref));
            $temp     = array();
            foreach ($crossref as $value) {
                $resultArray = $this->xml2array($value, $depth);

                if($resultArray) $temp = array_merge($temp, $resultArray);
            }
            $arXML = array_merge($arXML, array($key => $temp));
        }
        return $arXML;
    }

    /**
     * @param string $key
     * @param string $value
     * @param boolean $format
     *
     * @return array|string
     */
    private function setValueForSpecificKeys($key, $value, $format) {
        if ($key == 'birthday') {
            return $this->setValueForBirthdayKey($value, $format);
        }
        elseif ($key == 'item') {
            return array($value); // convert to apply array_merge()
        }

        return $value;
    }

    /**
     * Specific Key: Format to french date format
     *
     * @param string $value
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
}
