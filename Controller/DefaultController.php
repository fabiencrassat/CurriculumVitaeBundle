<?php
namespace Nimbusletruand\CurriculumVitaeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

use Nimbusletruand\CurriculumVitaeBundle\Entity\CurriculumVitae;

class DefaultController extends Controller
{
    private $Lang;
    private $ReadCVXml;
    private $FileToLoad;

    public function indexAction($cvxmlfile, $_locale)
    {
        $this->FileToLoad = (string) $cvxmlfile;
        $this->Lang = (string) $_locale;

        $this->ReadCVXml = new CurriculumVitae($this->FileToLoad, $this->Lang);

        return $this->render('NimbusletruandCurriculumVitaeBundle:CurriculumVitae:index.html.twig', array(
            'cvxmlfile'         => $this->FileToLoad,
            'languages'         => $this->ReadCVXml->getDropDownLanguages(),
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
}