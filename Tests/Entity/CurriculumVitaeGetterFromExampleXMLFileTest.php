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

class CurriculumVitaeGetterFromExampleXMLFileTest extends \PHPUnit\Framework\TestCase
{
    private $curriculumVitae;
    private $lang;
    private $interface;
    private $arrayToCompare;

    public function setUp() {
        $this->lang = 'en';
    }

    public function testGetDropDownLanguages() {
        $this->interface      = 'getDropDownLanguages';
        $this->arrayToCompare = [
            'en' => 'English',
            'fr' => 'Français',
            'es' => 'español'];

        $this->assertCVInterface();
    }

    public function testGetFollowMe() {
        $this->interface      = 'getFollowMe';
        $this->arrayToCompare = [
            'linkedin' => [
                'title' => 'Linked In',
                'url'   => 'http://www.linkedin.com',
                'icon'  => 'bundles/fabiencrassatcurriculumvitae/img/linkedin.png'],
            'viadeo' => [
                'title' => 'Viadeo',
                'url'   => 'http://www.viadeo.com',
                'icon'  => 'bundles/fabiencrassatcurriculumvitae/img/viadeo.png'],
            'monster' => [
                'title' => 'Monster',
                'url'   => 'http://beknown.com',
                'icon'  => 'bundles/fabiencrassatcurriculumvitae/img/monster.png'],
            'twitter' => [
                'title' => 'Twitter',
                'url'   => 'https://twitter.com',
                'icon'  => 'bundles/fabiencrassatcurriculumvitae/img/twitter.png'],
            'googleplus' => [
                'title' => 'Google+',
                'url'   => 'https://plus.google.com',
                'icon'  => 'bundles/fabiencrassatcurriculumvitae/img/googleplus.png'],
            'facebook' => [
                'title' => 'Facebook',
                'url'   => 'https://www.facebook.com',
                'icon'  => 'bundles/fabiencrassatcurriculumvitae/img/facebook.png'],
            'scrum' => [
                'title' => 'Scrum',
                'url'   => 'http://www.scrumalliance.org',
                'icon'  => 'bundles/fabiencrassatcurriculumvitae/img/scrum-alliance.png']
        ];

        $this->assertCVInterface();

        $this->lang = 'fr';
        $this->assertCVInterface();
    }

    public function testGetLookingFor() {
        $this->interface = 'getLookingFor';

        $this->arrayToCompare = [
            'experience'   => 'Curriculum Vitae Title',
            'presentation' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
            .' Aenean eu lectus facilisis, posuere leo laoreet, dignissim ligula. Praesent'
            .' ultricies dignissim diam vitae dictum. Donec sed nisi tortor. Proin tempus'
            .' scelerisque lectus, sit amet convallis mi semper a. Integer blandit a ligula'
            .' a volutpat. Ut dolor eros, interdum quis ante ac, tempus commodo odio. Suspendisse'
            .' ut nisi purus. Mauris vestibulum nibh sit amet turpis consequat pharetra. Duis at'
            .' adipiscing risus. Vivamus vitae orci ac felis porta euismod. Fusce sit amet metus'
            .' sem. Maecenas suscipit tincidunt ante, sed feugiat odio eleifend eu. Sed eu'
            .' ultricies ipsum. In cursus tincidunt elit a gravida. Nam eu aliquet leo. Maecenas'
            .' nibh leo, eleifend fermentum neque sit amet, viverra consequat lorem.'
        ];
        $this->assertCVInterface();

        $this->lang           = 'fr';
        $this->arrayToCompare = [
            'experience'   => 'Titre du curriculum vitae',
            'presentation' => 'Mauris rutrum justo ac bibendum ultrices. Mauris a dolor a diam'
            .' tempus ornare vel non urna. Donec a dui vel nunc ultrices porta non vitae felis.'
            .' Ut blandit ullamcorper orci. Quisque quis justo vitae nisl auctor laoreet non eget'
            .' mauris. Sed volutpat enim est, vitae vulputate nibh laoreet gravida. Duis nec'
            .' tincidunt ante. Nullam metus turpis, accumsan nec laoreet et, consectetur et'
            .' ligula. Curabitur convallis feugiat lorem, sit amet tincidunt arcu sollicitudin'
            .' vel. Aliquam erat volutpat. In odio elit, accumsan in facilisis at, ultricies'
            .' quis justo.'
        ];
        $this->assertCVInterface();
    }

    public function testGetExperiences() {
        $this->interface = 'getExperiences';

        $this->arrayToCompare = [
            'FirstExperience' => [
                'date'    => 'Jan 2007 - Present',
                'job'     => 'My current job',
                'society' => [
                    'name'    => 'My Company',
                    'address' => 'the address of the company',
                    'siteurl' => 'http://www.MyCompany.com'],
                'missions' => [
                    'item' => [
                        0 => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        1 => 'Suspendisse nec mauris eu orci dapibus mollis ac ac mi.']]],
            'SecondExperience' => [
                'collapse' => 'false',
                'date'     => 'Sept - Dec 2006',
                'job'      => 'My previous job',
                'society'  => [
                    'name'    => 'My Other Company',
                    'address' => 'the address of the company',
                    'siteurl' => 'http://www.MyOtherCompany.com']],
            'ThirdExperience' => [
                'date'    => 'Summer 2006',
                'job'     => 'A summer job',
                'society' => [
                    'name'    => 'A company wihtout site',
                    'address' => 'the address of the company'],
                'missions' => [
                    'item' => [
                        0 => 'Suspendisse et arcu eget est feugiat elementum.']]],
            'FourthExperience' => [
                'collapse' => 'true',
                'date'     => 'Before 2006',
                'job'      => 'The job of my life',
                'society'  => 'A society with a name per language',
                'missions' => [
                    'item' => [
                        0 => 'Suspendisse et arcu eget est feugiat elementum.']]]
        ];
        $this->assertCVInterface();

        $this->lang           = 'fr';
        $this->arrayToCompare = [
            'FirstExperience' => [
                'date'    => 'Jan. 2007 - Aujourd\'hui',
                'job'     => 'Mon poste actuel',
                'society' => [
                    'name'    => 'My Company',
                    'address' => 'the address of the company',
                    'siteurl' => 'http://www.MyCompany.com'],
                'missions' => [
                    'item' => [
                        0 => 'Donec gravida enim viverra tempor dignissim.',
                        1 => 'Sed a eros at mauris placerat adipiscing.']]],
            'SecondExperience' => [
                'collapse' => 'false',
                'date'     => 'Sept - Dec 2006',
                'job'      => 'Mon poste précédent',
                'society'  => [
                    'name'    => 'Mon autre compagnie',
                    'address' => 'l\'adresse de la compagnie',
                    'siteurl' => 'http://www.MyOtherCompany.com']],
            'ThirdExperience' => [
                'date'    => 'Summer 2006',
                'job'     => 'Un travail d\'été',
                'society' => [
                    'name'    => 'Une compagnie sans site',
                    'address' => 'l\'adresse de la compagnie'],
                'missions' => [
                    'item' => [
                        0 => 'Suspendisse et arcu eget est feugiat elementum.']]],
            'FourthExperience' => [
                'collapse' => 'true',
                'date'     => 'Before 2006',
                'job'      => 'Le job de ma vie',
                'society'  => 'Une société avec un nom par langue',
                'missions' => [
                    'item' => [
                        0 => 'Suspendisse et arcu eget est feugiat elementum.']]]
        ];
        $this->assertCVInterface();
    }

    public function testGetSkills() {
        $this->interface = 'getSkills';

        $this->arrayToCompare = [
            'Functional' => [
                'title' => 'Skills',
                'lines' => [
                    'success' => [
                        'percentage' => 90,
                        'class'      => 'success',
                        'striped'    => 'true',
                        'label'      => 'Increasing Skills',
                    ],
                    'otherSucess' => [
                        'percentage' => 90,
                        'class'      => 'success',
                        'label'      => 'success',
                    ],
                    'info' => [
                        'percentage' => 40,
                        'class'      => 'info',
                        'striped'    => 'false',
                        'label'      => 'info',
                    ],
                    'warning' => [
                        'percentage' => 20,
                        'class'      => 'warning',
                        'label'      => 'warning',
                    ],
                    'danger' => [
                        'percentage' => 10,
                        'class'      => 'danger',
                        'label'      => 'danger',
                    ],
                    'noClass' => [
                        'percentage' => 5,
                        'label'      => 'noClass',
                    ],
                    'nothing' => [
                        'label' => 'nothing',
                    ]
                ]
            ],
            'OtherSkill' => [
                'title' => 'One other',
                'lines' => [
                    'success' => [
                        'percentage' => 90,
                        'class'      => 'success',
                        'striped'    => 'false',
                        'label'      => 'Skills List',
                    ],
                    'info' => [
                        'percentage' => 40,
                        'class'      => 'info',
                        'striped'    => 'false',
                        'label'      => 'Label',
                    ],
                    'warning' => [
                        'percentage' => 20,
                        'class'      => 'warning',
                        'striped'    => 'false',
                        'label'      => 'Label',
                    ],
                    'danger' => [
                        'percentage' => 10,
                        'class'      => 'danger',
                        'striped'    => 'true',
                        'label'      => 'Label',
                    ]
                ]
            ]
        ];
        $this->assertCVInterface();

        $this->lang = 'fr';
        // Only set the french labels
        $arrayToChange                                            = $this->arrayToCompare;
        $arrayToChange['Functional']['title']                     = 'Compétences';
        $arrayToChange['Functional']['lines']['success']['label'] = 'Compétences grandissantes';
        $arrayToChange['OtherSkill']['title']                     = 'Une autre';
        $arrayToChange['OtherSkill']['lines']['success']['label'] = 'Liste de Compétences';

        $this->arrayToCompare = $arrayToChange;
        $this->assertCVInterface();
    }

    public function testGetEducations() {
        $this->interface = 'getEducations';

        $this->arrayToCompare = [
            'University' => [
                'date'         => '2002 - 2005',
                'education'    => 'My diploma in my university',
                'descriptions' => ['item' => [
                    0 => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc in'
                        .' auctor ipsum. Nullam venenatis sem.'
                ]]
            ],
            'HighSchool' => [
                'collapse'     => 'false',
                'date'         => 'June 2002',
                'education'    => 'My diploma in my high school',
                'descriptions' => ['item' => [
                    0 => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris elit'
                        .' dui, faucibus non laoreet luctus, dignissim at lectus. Quisque dignissim'
                        .' imperdiet consectetur. Praesent scelerisque neque.',
                    1 => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed pretium'
                        .' varius est sit amet consectetur. Suspendisse cursus dapibus egestas.'
                        .' Ut id augue quis mi scelerisque.'
                ]]
            ],
            'FirstSchool' => [
                'collapse'  => 'true',
                'date'      => 'June 2000',
                'education' => 'My diploma in my first school'
            ]
        ];
        $this->assertCVInterface();

        $this->lang           = 'fr';
        $this->arrayToCompare = [
            'University' => [
                'date'         => '2002 - 2005',
                'education'    => 'Mon diplôme dans mon université',
                'descriptions' => ['item' => [
                    0 => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris elit dui,'
                        .' faucibus non laoreet luctus, dignissim at lectus. Quisque dignissim'
                        .' imperdiet consectetur. Praesent scelerisque neque.',
                    1 => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed pretium'
                        .' varius est sit amet consectetur. Suspendisse cursus dapibus egestas.'
                        .' Ut id augue quis mi scelerisque.'
                ]]
            ],
            'HighSchool' => [
                'collapse'     => 'false',
                'date'         => 'Juin 2002',
                'education'    => 'Mon diplôme dans mon lycée',
                'descriptions' => ['item' => [
                    0 => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc in auctor'
                        .' ipsum. Nullam venenatis sem.'
                ]]
            ],
            'FirstSchool' => [
                'collapse'  => 'true',
                'date'      => 'Juin 2000',
                'education' => 'Mon diplôme dans mon collège'
            ]
        ];
        $this->assertCVInterface();
    }

    public function testGetLanguageSkills() {
        $this->interface = 'getLanguageSkills';

        $frenchFlag  = 'bundles/fabiencrassatcurriculumvitae/img/Flag-of-France.png';
        $englishFlag = 'bundles/fabiencrassatcurriculumvitae/img/Flag-of-United-Kingdom.png';

        $this->arrayToCompare = [
            'French' => [
                'title'       => 'French',
                'description' => 'My French level of the skill.',
                'icon'        => $frenchFlag
            ],
            'English' => [
                'title'       => 'English',
                'description' => 'My English level of the skill.',
                'icon'        => $englishFlag
            ]
        ];
        $this->assertCVInterface();

        $this->lang           = 'fr';
        $this->arrayToCompare = [
            'French' => [
                'title'       => 'Français',
                'description' => 'Mon niveau de Français.',
                'icon'        => $frenchFlag
            ],
            'English' => [
                'title'       => 'Anglais',
                'description' => 'Mon niveau d\'Anglais.',
                'icon'        => $englishFlag
            ]
        ];
        $this->assertCVInterface();
    }

    public function testGetMiscellaneous() {
        $this->interface = 'getMiscellaneous';

        $this->arrayToCompare = [
            'Practical' => [
                'title'         => 'Practices',
                'miscellaneous' => 'My practices'
            ]
        ];
        $this->assertCVInterface();

        $this->lang           = 'fr';
        $this->arrayToCompare = [
            'Practical' => [
                'title'         => 'Pratiques',
                'miscellaneous' => 'Mes pratiques',
                'description'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
                        .' Curabitur nec auctor nisl, eu fringilla nisi. Morbi scelerisque,'
                        .' est vitae mattis faucibus, felis sapien lobortis augue.'
            ]
        ];
        $this->assertCVInterface();
    }

    private function assertCVInterface($pathToFile = '/../../Resources/data/example.xml') {
        $this->curriculumVitae = new CurriculumVitae(__DIR__.$pathToFile, $this->lang);
        $this->assertEquals($this->arrayToCompare, $this->curriculumVitae->{$this->interface}());
    }
}
