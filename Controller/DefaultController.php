<?php
namespace Nimbusletruand\CurriculumVitaeBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Nimbusletruand\CurriculumVitaeBundle\Entity\CurriculumVitae;

class DefaultController extends ContainerAware
{
    private $Lang;
    private $ReadCVXml;
    private $FileToLoad;

    public function indexAction($cvxmlfile, $_locale)
    {
        $this->FileToLoad = (string) $cvxmlfile;
        $this->Lang = (string) $_locale;

        $pathToFile = __DIR__.'/../../../'.$this->container->getParameter('nimbusletruand_curriculumvitae.path_to_cv').'/'.$this->FileToLoad.'.xml';
        if (!is_file($pathToFile)) {
            throw new NotFoundHttpException('There is no curriculum vitae file defined for '.$this->FileToLoad.'.');
        }

        $this->ReadCVXml = new CurriculumVitae($pathToFile, $this->Lang);

        $exposedLanguages = $this->ReadCVXml->getDropDownLanguages();
        if (!array_key_exists($_locale, $exposedLanguages)) {
            throw new NotFoundHttpException('There is no curriculum vitae defined for this language');
        }

        return $this->container->get('templating')->renderResponse('NimbusletruandCurriculumVitaeBundle:CurriculumVitae:index.html.twig', array(
            'cvxmlfile'         => $this->FileToLoad,
            'languages'         => $exposedLanguages,
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