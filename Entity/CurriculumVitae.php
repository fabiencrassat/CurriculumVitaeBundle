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

use FabienCrassat\CurriculumVitaeBundle\Utility\LibXmlDisplayErrors;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

class CurriculumVitae extends Xml2arrayFunctions
{
    private $lang;
    private $curriculumVitae;
    private $pathToFile;
    private $interface;
    private $cvFile;
    private $xml2arrayFunctions;

    const IDENTITY_MYSELF = 'myself';
    const EXPERIENCES     = 'experiences';

    /**
     * @param string $pathToFile
     * @param string $lang
     */
    public function __construct($pathToFile, $lang = 'en') {
        $this->pathToFile = $pathToFile;
        $this->setFileName();
        $this->lang               = $lang;
        $this->curriculumVitae    = $this->getXmlCurriculumVitae();
        $this->xml2arrayFunctions = new Xml2arrayFunctions($this->curriculumVitae, $this->lang);
    }

    /**
     * @return null|array
     */
    public function getDropDownLanguages() {
        $this->interface = $this->curriculumVitae->{'langs'};
        $return          = $this->getXMLValue();
        if (!$return) {
            $return = [$this->lang => $this->lang];
        }

        return $return;
    }

    /**
     * @return array
     */
    public function getAnchors() {
        $anchorsAttribute = $this->curriculumVitae->xpath('curriculumVitae/*[attribute::anchor]');

        $anchors = [];
        foreach ($anchorsAttribute as $anchorsValue) {
            $anchor = (string) $anchorsValue['anchor'];
            $title  = $anchorsValue->xpath("anchorTitle[@lang='" . $this->lang . "']");
            if (count($title) == 0) {
                $title = $anchorsValue->xpath('anchorTitle');
            }
            $anchors[$anchor] = [
                'href'  => $anchor,
                'title' => (string) $title[0],
            ];
        }

        return $anchors;
    }

    /**
     * @return string
     */
    public function getHumanFileName() {
        $myName = $this->getMyName();
        if (empty($myName)) {
            return $this->cvFile;
        }

        $myCurrentJob = $this->getMyCurrentJob();
        if (empty($myCurrentJob)) {
            return $myName;
        }

        return $myName.' - '.$myCurrentJob;
    }

    /**
     * @return array<string,null|array<string,array>>
     */
    public function getCurriculumVitaeArray() {
        return [
            'identity'          => $this->getIdentity(),
            'followMe'          => $this->getFollowMe(),
            'lookingFor'        => $this->getLookingFor(),
            self::EXPERIENCES   => $this->getExperiences(),
            'skills'            => $this->getSkills(),
            'educations'        => $this->getEducations(),
            'languageSkills'    => $this->getLanguageSkills(),
            'miscellaneous'     => $this->getMiscellaneous()
        ];
    }

    /**
     * @return null|array<string,array>
     */
    public function getIdentity() {
        $this->interface = $this->curriculumVitae->curriculumVitae->identity->items;
        return $this->getXMLValue();
    }

    /**
     * @return null|array<string,array>
     */
    public function getFollowMe() {
        $this->interface = $this->curriculumVitae->curriculumVitae->followMe->items;
        return $this->getXMLValue();
    }

    /**
     * @return null|array<string,array>
     */
    public function getLookingFor() {
        $this->interface = $this->curriculumVitae->curriculumVitae->lookingFor;
        return $this->getXMLValue();
    }

    /**
     * @return null|array<string,array>
     */
    public function getExperiences() {
        $this->interface = $this->curriculumVitae->curriculumVitae->experiences->items;
        return $this->getXMLValue();
    }

    /**
     * @return null|array<string,array>
     */
    public function getSkills() {
        $this->interface = $this->curriculumVitae->curriculumVitae->skills->items;
        return $this->getXMLValue();
    }

    /**
     * @return null|array<string,array>
     */
    public function getEducations() {
        $this->interface = $this->curriculumVitae->curriculumVitae->educations->items;
        return $this->getXMLValue();
    }

    /**
     * @return null|array<string,array>
     */
    public function getLanguageSkills() {
        $this->interface = $this->curriculumVitae->curriculumVitae->languageSkills->items;
        return $this->getXMLValue();
    }

    /**
     * @return null|array<string,array>
     */
    public function getMiscellaneous() {
        $this->interface = $this->curriculumVitae->curriculumVitae->miscellaneous->items;
        return $this->getXMLValue();
    }

    private function setFileName() {
        $data = explode('/', $this->pathToFile);
        $data = $data[count($data) - 1];
        $data = explode('.', $data);

        $this->cvFile = $data[0];
    }

    /**
     * @return null|string
     */
    private function getMyName() {
        $identity = $this->getIdentity();

        if (isset($identity[self::IDENTITY_MYSELF]['name'])) {
            return $identity[self::IDENTITY_MYSELF]['name'];
        }

        return NULL;
    }

    /**
     * @return null|string
     */
    private function getMyCurrentJob() {
        $lookingFor = $this->getLookingFor();
        $experience = 'experience';
        if (isset($lookingFor[$experience]['job'])) {
            return (string) $lookingFor[$experience]['job'];
        } elseif (isset($lookingFor[$experience])) {
            return (string) $lookingFor[$experience];
        }

        return NULL;
    }

    /**
     * @return \SimpleXMLElement
     */
    private function getXmlCurriculumVitae() {
        if (is_null($this->pathToFile) || !is_file($this->pathToFile)) {
            throw new InvalidArgumentException('The path ' . $this->pathToFile . ' is not a valid path to file.');
        }
        $this->isValidXmlCurriculumVitae();

        return simplexml_load_file($this->pathToFile);
    }

    /**
     * @return boolean
     */
    private function isValidXmlCurriculumVitae() {
        // Active "user error handling"
        libxml_use_internal_errors(TRUE);

        // Instanciate of a DOMDocument
        $dom = new \DOMDocument('1.0');

        // Load the XML from the file
        $dom->load($this->pathToFile);

        // Validation duof the XML document
        $reflClass = new \ReflectionClass(get_class($this));
        $xsdFile   = dirname($reflClass->getFileName()).'/validator.xsd';
        $validate  = $dom->schemaValidate($xsdFile);
        if (!$validate) {
            $libxmlDisplayErrors = new LibXmlDisplayErrors;
            throw new InvalidArgumentException($libxmlDisplayErrors->displayErrors());
        }

        return $validate;
    }

    /**
     * @return null|array<string,array>
     */
    private function getXMLValue() {
        if (!$this->interface) {
            return NULL;
        }

        return $this->xml2arrayFunctions->xml2array($this->interface);
    }
}
