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
        $this->file     = $file;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param integer $recursiveDepth
     * @param boolean $format
     *
     * @return null|array
     */
    public function xml2array(\SimpleXMLElement $xml, $recursiveDepth = 0, $format = TRUE) {
        $recursiveDepth++;
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
        $value  = $this->setSpecificAttributeAge($xml, $recursiveDepth, $value);
        $result = $this->retrieveSpecificAttributeCrossRef($xml, $recursiveDepth, $result, $key);
        // Standard Attributes
        $result = $this->setStandardAttributes($xml, $result, $key);
        // Specific Key
        $value = $this->setValueForSpecificKeys($key, $value, $format);

        $result = $this->setValue($result, $key, $value);
        $result = $this->setChildren($xml, $recursiveDepth, $key, $result);

        return $result;
    }

    /**
     * @param array $arrayToSet
     * @param string $key
     * @param array|string $value
     *
     * @return array
     */
    private function setValue($arrayToSet, $key, $value) {
        if ($value <> '') {
            return array_merge($arrayToSet, array($key => $value));
        }
        return $arrayToSet;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param integer $depth
     * @param string $key
     * @param array $arrayXML
     *
     * @return array
     */
    private function setChildren(\SimpleXMLElement $xml, $depth, $key, array $arrayXML) {
        $return = $arrayXML;
        if ($xml->children()->count() > 0) {
            foreach($xml->children() as $childValue) {
                $child = $this->xml2array($childValue, $depth);
                if ($depth > 1 && ! empty($child)) {
                    $return = array_merge_recursive($return, array($key => $child));
                } elseif (! empty($child)) {
                    $return = array_merge_recursive($return, $child);
                }
            }
        }
        return $return;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param array $arrayToMerge
     * @param string $key
     *
     * @return array
     */
    private function setStandardAttributes(\SimpleXMLElement $xml, $arrayToMerge, $key) {
        // Standard Attributes (without Specific thanks to unset())
        $attributes = array();
        foreach($xml->attributes() as $attributeKey => $attributeValue) {
            if ($this->isStandardAttributes($attributeKey)) {
                $attributes[$attributeKey] = trim($attributeValue);
            }
        }
        if (count($attributes) > 0) {
            return array_merge_recursive($arrayToMerge, array($key => $attributes));
        }
        return $arrayToMerge;
    }

    private function isStandardAttributes($attribute)
    {
        return $attribute <> 'id'
            && $attribute <> 'ref'
            && $attribute <> 'lang'
            && $attribute <> 'crossref';
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param string $keyValue
     *
     * @return string
     */
    private function setSpecificAttributeKeyWithGivenId(\SimpleXMLElement $xml, $keyValue) {
        // Specific Attribute: change the key with the given id
        if ($xml->attributes()->id) {
            return (string) $xml->attributes()->id;
        }
        return $keyValue;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param integer $depth
     * @param string $age
     *
     * @return string
     */
    private function setSpecificAttributeAge(\SimpleXMLElement $xml, $depth, $age) {
        // Specific Attribute: Retreive the age
        if ($xml->attributes()->getAge) {
            $crossref = $this->file->xpath(trim($xml->attributes()->getAge));
            $birthday = $this->xml2array(clone $crossref[0], $depth, FALSE);
            $birthday = implode('', $birthday);

            $ageCalculator = new AgeCalculator((string) $birthday);

            return (string) $ageCalculator->age();
        }
        return $age;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param integer $depth
     * @param array $arrayXML
     * @param string $key
     *
     * @return array
     */
    private function retrieveSpecificAttributeCrossRef(\SimpleXMLElement $xml, $depth, array $arrayXML, $key) {
        // Specific Attribute: Retrieve the given crossref
        if ($xml->attributes()->crossref) {
            $crossref = $this->file->xpath(trim($xml->attributes()->crossref));
            $temp     = array();
            foreach ($crossref as $value) {
                $resultArray = $this->xml2array($value, $depth);

                if($resultArray) $temp = array_merge($temp, $resultArray);
            }
            return array_merge($arrayXML, array($key => $temp));
        }
        return $arrayXML;
    }

    /**
     * @param string $key
     * @param string $value
     * @param boolean $format
     *
     * @return string|string[]
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
     * @param string $date
     * @param boolean $format
     *
     * @return string
     */
    private function setValueForBirthdayKey($date, $format) {
        if ($format) {
            setlocale(LC_TIME, array('fra_fra', 'fr', 'fr_FR', 'fr_FR.UTF8'));
            return strftime('%d %B %Y', strtotime(date($date)));
        }
        return $date;
    }
}
