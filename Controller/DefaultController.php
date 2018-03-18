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
use FabienCrassat\CurriculumVitaeBundle\DependencyInjection\FabienCrassatCurriculumVitaeExtension;
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
    private $parameters = [];

    const URL_CVXMLFILE = 'cvxmlfile';
    const PDF_A5SYS     = 'a5sys_pdf.pdf_service';
    const PDF_SNAPPY    = 'knp_snappy.pdf';

    /**
     * @return Response
     */
    public function indexAction($cvxmlfile = NULL)
    {
        if ($cvxmlfile) {
            $path = [
                '_controller'       => 'FabienCrassatCurriculumVitaeBundle:Default:display',
                self::URL_CVXMLFILE => $cvxmlfile,
                '_locale'           => $this->lang,
            ];

            $request    = $this->container->get('request');
            $subRequest = $request->duplicate([], NULL, $path);

            $httpKernel = $this->container->get('http_kernel');

            return $httpKernel->handle(
                $subRequest,
                HttpKernelInterface::SUB_REQUEST
            );
        }

        $this->initialization($cvxmlfile);
        return new RedirectResponse($this->container->get('router')->generate(
            'fabiencrassat_curriculumvitae_cvxmlfileonly',
            [self::URL_CVXMLFILE => $this->cvxmlfile]),
            301);
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
                $encoders    = [new XmlEncoder('CurriculumVitae'), new JsonEncoder()];
                $normalizers = [new GetSetMethodNormalizer()];
                $serializer  = new Serializer($normalizers, $encoders);

                $response = new Response();
                $response->setContent($serializer->serialize($this->parameters, 'xml'));
                $response->headers->set('Content-Type', 'application/xml');

                return $response;
            default:
                return $this->container->get('templating')->renderResponse(
                    $this->container->getParameter(FabienCrassatCurriculumVitaeExtension::TEMPLATE),
                    $this->parameters);
        }
    }

    /**
     * @return Response
     */
    public function exportPDFAction($cvxmlfile, $_locale)
    {
        if (!$this->hasExportPDF()) {
            throw new NotFoundHttpException('No export PDF service installed.');
        }

        $this->initialization($cvxmlfile, $_locale);
        $this->setViewParameters();

        $html     = $this->container->get('templating')->render(
                    'FabienCrassatCurriculumVitaeBundle:CurriculumVitae:index.pdf.twig',
                    $this->parameters);
        $filename = $this->curriculumVitae->getHumanFileName().'.pdf';

        $hasPdfService = false;
        $content       = '';
        if (!$hasPdfService && $this->container->has(self::PDF_A5SYS)) {
            $hasPdfService = true;
            $content       = $this->container->get(self::PDF_A5SYS)->sendPDF($html, $filename);
        }
        if (!$hasPdfService && $this->container->has(self::PDF_SNAPPY)) {
            $content = $this->container->get(self::PDF_SNAPPY)->getOutputFromHtml($html);
        }

        return new Response($content, 200,
            ['Content-Type'       => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"']
        );
    }

    private function initialization($file = NULL, $lang = NULL)
    {
        $this->cvxmlfile = $file;
        if (!$this->cvxmlfile) {
            // Retreive the CV file depending the configuration
            $this->cvxmlfile = $this->container->getParameter(FabienCrassatCurriculumVitaeExtension::DEFAULT_CV);
        }
        // Check the file in the filesystem
        $this->pathToFile =
            $this->container->getParameter(FabienCrassatCurriculumVitaeExtension::PATH_TO_CV)
            .'/'.$this->cvxmlfile.'.xml';

        if (!is_file($this->pathToFile)) {
            throw new NotFoundHttpException(
                'There is no curriculum vitae file defined for '.$this->cvxmlfile.' ('.$this->pathToFile.').');
        }

        $this->lang = $lang;
        if (!$this->lang) {
            $this->lang = $this->container->getParameter(FabienCrassatCurriculumVitaeExtension::DEFAULT_LANG);
        }

        $this->readCVFile();
    }

    private function readCVFile() {
        // Read the Curriculum Vitae
        $this->curriculumVitae = new CurriculumVitae($this->pathToFile, $this->lang);

        // Check if there is at least 1 language defined
        $this->exposedLanguages = $this->curriculumVitae->getDropDownLanguages();
        if (is_array($this->exposedLanguages) && !array_key_exists($this->lang, $this->exposedLanguages)) {
            throw new NotFoundHttpException('There is no curriculum vitae defined for the language '.$this->lang);
        }
    }

    private function setViewParameters()
    {
        if ($this->requestFormat != 'json' && $this->requestFormat != 'xml') {
            $this->setToolParameters();
        }
        $this->setParameters($this->curriculumVitae->getCurriculumVitaeArray());
    }

    private function hasExportPDF()
    {
        return $this->container->has(self::PDF_SNAPPY) xor $this->container->has(self::PDF_A5SYS);
    }

    private function hasSecureDisplayBundle()
    {
        return $this->container->has('netinfluence.twig.secure_display_extension');
    }

    private function setToolParameters()
    {
        $this->setParameters([
            self::URL_CVXMLFILE      => $this->cvxmlfile,
            'languageView'           => $this->lang,
            'languages'              => $this->exposedLanguages,
            'anchors'                => $this->curriculumVitae->getAnchors(),
            'hasExportPDF'           => $this->hasExportPDF(),
            'hasSecureDisplayBundle' => $this->hasSecureDisplayBundle()
        ]);
    }

    /**
     * @param array $parametersToAdd
     */
    private function setParameters(array $parametersToAdd)
    {
        $this->parameters = array_merge($this->parameters, $parametersToAdd);
    }
}
