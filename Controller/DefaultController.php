<?php

/*
 * This file is part of the Nimbusletruand\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimbusletruand\CurriculumVitaeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Nimbusletruand\CurriculumVitaeBundle\Entity\CurriculumVitae;

class DefaultController extends Controller
{
    private $Lang;
    private $ReadCVXml;
    private $FileToLoad;
    private $exposedLanguages;
    private $hasSnappyPDF;

    public function indexAction($cvxmlfile, $_locale)
    {
        $this->readCVFile($cvxmlfile, $_locale);

        $templateVariables = array_merge($this->defineCVViewVariables(), array('hasSnappyPDF' => $this->hasSnappyPDF));
        return $this->container->get('templating')->renderResponse(
            $this->container->getParameter('nimbusletruand_curriculumvitae.template'), $templateVariables);
    }

    public function exportPDFAction($cvxmlfile, $_locale)
    {
        $this->readCVFile($cvxmlfile, $_locale);

        if (!$this->hasSnappyPDF) {
            throw new NotFoundHttpException('knp_snappy.pdf is non-existent');
        };

        $html = $this->container->get('templating')->render(
            "NimbusletruandCurriculumVitaeBundle:CurriculumVitae:index.pdf.twig",  $this->defineCVViewVariables());

        $identity = $this->ReadCVXml->getIdentity();
        $lookingFor = $this->ReadCVXml->getLookingFor();
        $filename = $identity['myself']['Name'].' - '.$lookingFor['experience']['job'].'.pdf';

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="'.$filename.'"'
            )
        );
    }

    private function readCVFile($cvxmlfile, $_locale)
    {
        // Retreive the CV file depending the configuration
        if ($this->container->getParameter('nimbusletruand_curriculumvitae.default_cv') == $cvxmlfile) {
            $custo_default_cv = $this->container->getParameter('nimbusletruand_curriculumvitae.custo_default_cv');
            if(!is_null($custo_default_cv)) {
                $cvxmlfile = $custo_default_cv;
            }
        }
        $this->FileToLoad = (string) $cvxmlfile;
        $this->Lang = (string) $_locale;

        // Check the file in the filesystem
        $pathToFile = $this->container->getParameter('nimbusletruand_curriculumvitae.path_to_cv').'/'.$this->FileToLoad.'.xml';
        if (!is_file($pathToFile)) {
            throw new NotFoundHttpException('There is no curriculum vitae file defined for '.$this->FileToLoad.' ('.$pathToFile.').');
        }

        // Read the Curriculum Vitae
        $this->ReadCVXml = new CurriculumVitae($pathToFile, $this->Lang);

        // Check if there is at least 1 language defined
        $this->exposedLanguages = $this->ReadCVXml->getDropDownLanguages();
        if(is_array($this->exposedLanguages)) {
        if (!array_key_exists($_locale, $this->exposedLanguages)) {
            throw new NotFoundHttpException('There is no curriculum vitae defined for this language');
        }};

        // Check if knp_snappy is existent
        $this->hasSnappyPDF = $this->container->has('knp_snappy.pdf');
    }
    private function defineCVViewVariables()
    {
        return array(
                'cvxmlfile'         => $this->FileToLoad,
                'languageView'      => $this->Lang,
                'languages'         => $this->exposedLanguages,
                'anchors'           => $this->ReadCVXml->getAnchors(),
                'identity'          => $this->ReadCVXml->getIdentity(),
                'followMe'          => $this->ReadCVXml->getFollowMe(),
                'lookingFor'        => $this->ReadCVXml->getLookingFor(),
                'experiences'       => $this->ReadCVXml->getExperiences(),
                'skills'            => $this->ReadCVXml->getSkills(),
                'educations'        => $this->ReadCVXml->getEducations(),
                'languageSkills'    => $this->ReadCVXml->getLanguageSkills(),
                'miscellaneous'     => $this->ReadCVXml->getMiscellaneous(),
                'society'           => $this->ReadCVXml->getSociety()
        );
    }
};