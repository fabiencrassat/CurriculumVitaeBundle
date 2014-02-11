# Understand the link beetween xml file and twig variables

Twig objects return by the controller:

'cvxmlfile'         => "route_to_xml_file",
'languages'         => ["key", "language"], the key is used by _locale
'anchors'           => [identity/followMe/experiences/skills/educations/languageSkills/miscellaneous
                            href, 
                            title
                        ],
'identity'          => [myself
                            Name
                        ],
'followMe'          => $this->ReadCVXml->getFollowMe(),
'lookingFor'        => $this->ReadCVXml->getLookingFor(),
'experiences'       => $this->ReadCVXml->getExperiences(),
'skills'            => $this->ReadCVXml->getSkills(),
'educations'        => $this->ReadCVXml->getEducations(),
'languageSkills'    => $this->ReadCVXml->getLanguageSkills(),
'miscellaneous'     => $this->ReadCVXml->getMiscellaneous(),
'society'           => $this->ReadCVXml->getSociety()
