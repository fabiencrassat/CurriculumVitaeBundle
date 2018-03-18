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

use FabienCrassat\CurriculumVitaeBundle\Entity\CurriculumVitae;

class CurriculumVitaeGetterFromBackboneXMLFileTest extends \PHPUnit\Framework\TestCase
{
    private $curriculumVitae;
    private $lang;

    public function setUp() {
        $this->lang = 'en';
    }

    public function testGetLookingForAndExperiencesAndHumanFileName() {
        $this->curriculumVitae = new CurriculumVitae(__DIR__.'/../Resources/data/backbone.xml', $this->lang);

        $result = [];
        $result = array_merge($result, ['lookingFor'                 => $this->curriculumVitae->getLookingFor()]);
        $result = array_merge($result, [CurriculumVitae::EXPERIENCES => $this->curriculumVitae->getExperiences()]);
        $result = array_merge($result, ['pdfFile'                    => $this->curriculumVitae->getHumanFileName()]);

        $expected = [
            'lookingFor' => [
                'experience' => [
                    'date'    => 'Date',
                    'job'     => 'The job',
                    'society' => [
                        'name'    => 'My Company',
                        'address' => 'The address of the company',
                        'siteurl' => 'http://www.MyCompany.com']],
                'presentation' => 'A presentation'],
            CurriculumVitae::EXPERIENCES => [
                'LastJob' => [
                    'date'    => 'Date',
                    'job'     => 'The job',
                    'society' => [
                        'name'    => 'My Company',
                        'address' => 'The address of the company',
                        'siteurl' => 'http://www.MyCompany.com']]],
            'pdfFile' => 'First Name Last Name - The job'
        ];
        $this->assertEquals($expected, $result);
    }

    public function testGetAnchorsWithNoLang() {
        $this->curriculumVitae = new CurriculumVitae(__DIR__.'/../Resources/data/backbone.xml');
        $href                  = 'href';
        $title                 = 'title';
        $identity              = 'identity';
        $followMe              = 'followMe';
        $experiences           = 'experiences';
        $skills                = 'skills';
        $educations            = 'educations';
        $languageSkills        = 'languageSkills';
        $miscellaneous         = 'miscellaneous';

        $anchors = $this->curriculumVitae->getAnchors();
        if (is_array($anchors)) {
            $this->assertEquals([
                $identity => [
                    $href  => $identity,
                    $title => $identity],
                $followMe => [
                    $href  => $followMe,
                    $title => $followMe],
                $experiences => [
                    $href  => $experiences,
                    $title => $experiences],
                $skills => [
                    $href  => $skills,
                    $title => $skills],
                $educations => [
                    $href  => $educations,
                    $title => $educations],
                $languageSkills => [
                    $href  => $languageSkills,
                    $title => $languageSkills],
                $miscellaneous => [
                    $href  => $miscellaneous,
                    $title => $miscellaneous]],
                $anchors
            );
        }
    }
}
