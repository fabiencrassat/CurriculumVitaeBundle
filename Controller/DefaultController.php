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
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

use Nimbusletruand\CurriculumVitaeBundle\Entity\CurriculumVitae;

class DefaultController extends Controller
{
    private $Lang;
    private $ReadCVXml;
    private $FileToLoad;
    private $exposedLanguages;

    public function indexAction($cvxmlfile, $_locale)
    {
        $this->readCVFile($cvxmlfile, $_locale);

        return $this->container->get('templating')->renderResponse(
            $this->container->getParameter('nimbusletruand_curriculumvitae.template'), array(
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
        ));
    }

    public function exportPDFAction($cvxmlfile, $_locale)
    {
        $this->readCVFile($cvxmlfile, $_locale);

        $html = $this->container->get('templating')->render(
            "NimbusletruandCurriculumVitaeBundle:CurriculumVitae:index.pdf.twig", array(
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
        ));

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
        if ($this->container->getParameter('nimbusletruand_curriculumvitae.default_cv') == $cvxmlfile) {
            $custo_default_cv = $this->container->getParameter('nimbusletruand_curriculumvitae.custo_default_cv');
            if(!is_null($custo_default_cv)) {
                $cvxmlfile = $custo_default_cv;
            }
        }
        $this->FileToLoad = (string) $cvxmlfile;
        $this->Lang = (string) $_locale;

        $pathToFile = $this->container->getParameter('nimbusletruand_curriculumvitae.path_to_cv').'/'.$this->FileToLoad.'.xml';
        if (!is_file($pathToFile)) {
            throw new NotFoundHttpException('There is no curriculum vitae file defined for '.$this->FileToLoad.' ('.$pathToFile.').');
        }

        $this->ReadCVXml = new CurriculumVitae($pathToFile, $this->Lang);

        $this->exposedLanguages = $this->ReadCVXml->getDropDownLanguages();
        if (!array_key_exists($_locale, $this->exposedLanguages)) {
            throw new NotFoundHttpException('There is no curriculum vitae defined for this language');
        };
    }
}