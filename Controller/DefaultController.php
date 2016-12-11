<?php

/*
 * This file is part of the FabienCrassat\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabienCrassat\CurriculumVitaeBundle\Controller;

use FabienCrassat\CurriculumVitaeBundle\Entity\CurriculumVitae;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class DefaultController implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    private $cvxmlfile;
    private $pathToFile;
    private $lang;
    private $curriculumVitae;
    private $exposedLanguages;
    private $requestFormat;
    private $parameters = array();

    /**
     * @return Response
     */
    public function indexAction($cvxmlfile = NULL)
    {
        if($cvxmlfile) {
            $path = array(
                '_controller' => 'FabienCrassatCurriculumVitaeBundle:Default:display',
                'cvxmlfile'   => $cvxmlfile,
                '_locale'     => $this->lang,
            );

            $request    = $this->container->get('request');
            $subRequest = $request->duplicate(array(), NULL, $path);

            $httpKernel = $this->container->get('http_kernel');
            $response   = $httpKernel->handle(
                $subRequest,
                HttpKernelInterface::SUB_REQUEST
            );
            return $response;
        }
        
        $this->initialization($cvxmlfile);
        return new RedirectResponse($this->container->get('router')->generate(
            'fabiencrassat_curriculumvitae_cvxmlfileonly',
            array(
                'cvxmlfile'   => $this->cvxmlfile,
            )), 301);
    }

    /**
     * @return Response
     */
    public function displayAction($cvxmlfile, $_locale, Request $request)
    {
        $this->initialization($cvxmlfile, $_locale);
        $this->requestFormat = $request->getRequestFormat();
        $this->setViewParameters();

        switch ($this->requestFormat) {
            case 'json':
                return new Response(json_encode($this->parameters));
            case 'xml':
                //initialisation du serializer
                $encoders    = array(new XmlEncoder('CurriculumVitae'), new JsonEncoder());
                $normalizers = array(new GetSetMethodNormalizer());
                $serializer  = new Serializer($normalizers, $encoders);

                $response = new Response();
                $response->setContent($serializer->serialize($this->parameters, 'xml'));
                $response->headers->set('Content-Type', 'application/xml');

                return $response;
            default:
                return $this->container->get('templating')->renderResponse(
                    $this->container->getParameter('fabiencrassat_curriculumvitae.template'),
                    $this->parameters);
        }
    }

    public function exportPDFAction($cvxmlfile, $_locale)
    {
        $this->initialization($cvxmlfile, $_locale);
        $this->setViewParameters();

        if (!$this->container->has('knp_snappy.pdf')) {
            throw new NotFoundHttpException('knp_snappy.pdf is non-existent');
        };

        $html = $this->container->get('templating')->render(
            'FabienCrassatCurriculumVitaeBundle:CurriculumVitae:index.pdf.twig',
            $this->parameters);

        return new Response($this->container->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array('Content-Type'        => 'application/pdf',
                  'Content-Disposition' => 'attachment; filename="'.$this->curriculumVitae->getHumanFileName().'.pdf"')
        );
    }

    private function initialization($file = NULL, $lang = NULL)
    {
        $this->cvxmlfile = $file;
        if (!$this->cvxmlfile) {
            // Retreive the CV file depending the configuration
            $this->cvxmlfile = $this->container->getParameter('fabiencrassat_curriculumvitae.default_cv');
        }
        // Check the file in the filesystem
        $this->pathToFile = 
            $this->container->getParameter('fabiencrassat_curriculumvitae.path_to_cv')
            .'/'.$this->cvxmlfile.'.xml';
        
        if (!is_file($this->pathToFile)) {
            throw new NotFoundHttpException(
                'There is no curriculum vitae file defined for '.$this->cvxmlfile.' ('.$this->pathToFile.').');
        }

        $this->lang = $lang;
        if (!$this->lang) {
            $this->lang = $this->container->getParameter('fabiencrassat_curriculumvitae.default_lang');
        }
        
        $this->readCVFile();
    }

    private function readCVFile() {
        // Read the Curriculum Vitae
        $this->curriculumVitae = new CurriculumVitae($this->pathToFile, $this->lang);

        // Check if there is at least 1 language defined
        $this->exposedLanguages = $this->curriculumVitae->getDropDownLanguages();
        if(is_array($this->exposedLanguages)) {
            if (!array_key_exists($this->lang, $this->exposedLanguages)) {
                throw new NotFoundHttpException('There is no curriculum vitae defined for the language '.$this->lang);
            }
        }
    }

    private function setViewParameters()
    {
        if ($this->requestFormat != 'json' && $this->requestFormat != 'xml') {
            $this->setToolParameters();
        }
        $this->setCoreParameters();
    }

    private function setToolParameters()
    {
        $this->setParameters(array(
            'cvxmlfile'    => $this->cvxmlfile,
            'languageView' => $this->lang,
            'languages'    => $this->exposedLanguages,
            'anchors'      => $this->curriculumVitae->getAnchors(),
            'hasSnappyPDF' => $this->container->has('knp_snappy.pdf'),
        ));
    }

    private function setCoreParameters()
    {
        $this->setParameters(array(
            'identity'          => $this->curriculumVitae->getIdentity(),
            'followMe'          => $this->curriculumVitae->getFollowMe(),
            'lookingFor'        => $this->curriculumVitae->getLookingFor(),
            'experiences'       => $this->curriculumVitae->getExperiences(),
            'skills'            => $this->curriculumVitae->getSkills(),
            'educations'        => $this->curriculumVitae->getEducations(),
            'languageSkills'    => $this->curriculumVitae->getLanguageSkills(),
            'miscellaneous'     => $this->curriculumVitae->getMiscellaneous(),
        ));
    }

    /**
     * @param array $parametersToAdd
     */
    private function setParameters(array $parametersToAdd)
    {
        $this->parameters = array_merge($this->parameters, $parametersToAdd);
    }
}
