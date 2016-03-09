<?php

/*
 * This file is part of the FabienCrassat\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabienCrassat\CurriculumVitaeBundle\Tests\Entity;

use FabienCrassat\CurriculumVitaeBundle\Entity\Xml2arrayFunctions;

class Xml2arrayFunctionsTest extends \PHPUnit_Framework_TestCase
{
    private $XML;
    private $Xml2arrayFunctions;
    private $CV;

    public function testXml2arrayEmpty() {
        $string = <<<XML
<?xml version='1.0'?>
<document>
</document>
XML;
        $expected = array();

        $this->XML = simplexml_load_string($string);
        $this->Xml2arrayFunctions = new Xml2arrayFunctions("<xml></xml>");

        $result = $this->Xml2arrayFunctions->xml2array($this->XML);
        $this->assertEquals($expected, $result);
    }

    public function testXml2arrayWithBadLanguage() {
        $string = <<<XML
<?xml version='1.0'?>
<document>
    <node lang='unknown'>something we don't want</node>
</document>
XML;
        $expected = array();

        $this->XML = simplexml_load_string($string);
        $this->Xml2arrayFunctions = new Xml2arrayFunctions("<xml></xml>");

        $result = $this->Xml2arrayFunctions->xml2array($this->XML);
        $this->assertEquals($expected, $result);
    }

    public function testXml2arraySimple() {
        $string = <<<XML
<?xml version='1.0'?>
<document>
 <title>Forty What?</title>
 <from>Joe</from>
 <to>Jane</to>
 <body>
  I know that's the answer -- but what's the question?
 </body>
</document>
XML;
        $expected = array(
            'title' => "Forty What?",
            'from'  => "Joe",
            'to'    => "Jane",
            'body'  => "I know that's the answer -- but what's the question?"
        );

        $this->XML = simplexml_load_string($string);
        $this->Xml2arrayFunctions = new Xml2arrayFunctions("<xml></xml>");

        $result = $this->Xml2arrayFunctions->xml2array($this->XML);
        $this->assertEquals($expected, $result);
    }

    public function testXml2arrayWithAttribute() {
        $string = <<<XML
<?xml version='1.0'?>
<document>
 <attr attributekey="attributevalue">We don't care of this value!!!</attr>
 <value>value</value>
</document>
XML;
        $expected = array(
            'attr'   => array(
                'attributekey' => "attributevalue"),
            'value'  => "value"
        );

        $this->XML = simplexml_load_string($string);
        $this->Xml2arrayFunctions = new Xml2arrayFunctions("<xml></xml>");

        $result = $this->Xml2arrayFunctions->xml2array($this->XML);
        $this->assertEquals($expected, $result);
    }

    public function testXml2arrayWithCrossRefDepth1() {
        $CV = <<<XML
<?xml version='1.0'?>
<document>
 <society crossref="societies/society[@ref='OneSociety']"></society>
 <societies>
  <society ref="OneSociety">
    <name>OneSociety</name>
    <address>An address</address>
    <siteurl>http://www.google.com</siteurl>
  </society>
 </societies>
</document>
XML;
        $string = <<<XML
<?xml version='1.0'?>
<document>
 <society crossref="societies/society[@ref='OneSociety']"></society>
</document>
XML;
        $expected = array('society' => array(
            'name'      => "OneSociety",
            'address'   => "An address",
            'siteurl'   => "http://www.google.com",
            'society'   => array('ref' => "OneSociety")
        ));

        $this->XML = simplexml_load_string($string);
        $this->CV = simplexml_load_string($CV);
        $this->Xml2arrayFunctions = new Xml2arrayFunctions($this->CV);

        $result = $this->Xml2arrayFunctions->xml2array($this->XML);
        $this->assertEquals($expected, $result);
    }

    public function testXml2arrayWithCrossRefDepth2() {
        $CV = <<<XML
<?xml version='1.0'?>
<document>
 <job crossref="experience[@id='OneExperience']"></job>
 <experience id="OneExperience">
  <society crossref="societies/society[@ref='OneSociety']"></society>
  <job>My first job</job>
 </experience>
 <societies>
  <society ref="OneSociety">
    <name>OneSociety</name>
    <address>An address</address>
    <siteurl>http://www.google.com</siteurl>
  </society>
 </societies>
</document>
XML;
        $string = <<<XML
<?xml version='1.0'?>
<document>
 <job crossref="experience[@id='OneExperience']"></job>
</document>
XML;
        $expected = array('job' => array(
            'society' => array(
                'name'      => "OneSociety",
                'address'   => "An address",
                'siteurl'   => "http://www.google.com",
                'society'   => array('ref' => "OneSociety")),
            'job'   => "My first job"
        ));

        $this->XML = simplexml_load_string($string);
        $this->CV = simplexml_load_string($CV);
        $this->Xml2arrayFunctions = new Xml2arrayFunctions($this->CV);

        $result = $this->Xml2arrayFunctions->xml2array($this->XML);
        $this->assertEquals($expected, $result);

        $string = <<<XML
<?xml version='1.0'?>
<document>
 <experience id="OneExperience">
  <society crossref="societies/society[@ref='OneSociety']"></society>
  <job>My first job</job>
 </experience>
</document>
XML;
        $this->XML = simplexml_load_string($string);
        $this->Xml2arrayFunctions = new Xml2arrayFunctions($this->CV);
        $this->assertEquals($expected, $result);

        $string = <<<XML
<?xml version='1.0'?>
<document>
  <society crossref="societies/society[@ref='OneSociety']"></society>
</document>
XML;
        $this->XML = simplexml_load_string($string);
        $this->Xml2arrayFunctions = new Xml2arrayFunctions($this->CV);
        $this->assertEquals($expected, $result);
    }

    public function testXml2arrayWithCrossRef() {
        $CV = <<<XML
<?xml version='1.0'?>
<document>
 <job crossref="experience[@id='OneExperience']"></job>
 <experience id="OneExperience">
  <society crossref="societies/society[@ref='OneSociety']"></society>
  <job>My first job</job>
 </experience>
 <societies>
  <society ref="OneSociety">
    <name>OneSociety</name>
    <address>An address</address>
    <siteurl>http://www.google.com</siteurl>
  </society>
 </societies>
</document>
XML;
        $string = <<<XML
<?xml version='1.0'?>
<document>
 <job crossref="experience[@id='OneExperience']"></job>
</document>
XML;
        $expected = array('job' => array(
            'society' => array(
                'name'      => "OneSociety",
                'address'   => "An address",
                'siteurl'   => "http://www.google.com",
                'society'   => array('ref' => "OneSociety")),
            'job'   => "My first job"
        ));

        $this->XML = simplexml_load_string($string);
        $this->CV = simplexml_load_string($CV);
        $this->Xml2arrayFunctions = new Xml2arrayFunctions($this->CV);

        $result = $this->Xml2arrayFunctions->xml2array($this->XML);
        $this->assertEquals($expected, $result);
    }
}
