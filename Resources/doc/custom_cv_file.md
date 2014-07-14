# Using a custom Curriculum Vitae File

The structure of the Curriculum is not store in database, but in xml file.

You can see an example of all you can do with the [example.xml](../data/example.xml "example.xml")

## Summarize
*   [Main Structure](#main-structure "Main Structure")
*   [Block tag](#block-tag "Block tag")
    *   [Lang Block](#lang-block "Lang Block")
    *   [CurriculumVitae Block](#curriculumvitae-block "CurriculumVitae Block")
        *   [Identity block](#identity-block "Identity block")
            *   [Myself tag](#myself-tag "Myself tag")
            *   [Address tag](#address-tag "Address tag")
            *   [Contact tag](#contact-tag "Contact tag")
            *   [Social tag](#social-tag "Social tag")
        *   [FollowMe block](#followme-block "FollowMe block")
        *   [LookingFor block](#lookingfor-block "LookingFor block")
        *   [Experiences block](#experiences-block "Experiences block")
        *   [Skills block](#skills-block "Skills block")
        *   [Education block](#education-block "Education block")
        *   [LanguageSkills block](#languageskills-block "LanguageSkills block")
        *   [Miscellaneous block](#miscellaneous-block "Miscellaneous block")
    *   [Society block](#society-block "Society block") [todo]

## Main Structure

The main structure you need to allow the personlization of the CV is:

``` xml
<xml version="1.0" encoding="UTF-8">
<lang>
</lang>
<CurriculumVitae>
</CurriculumVitae>
</xml>
```

## Block tag

### Lang Block

#### Declaration

It is all languages you want to expose in your curriculum vitae.

```<en>``` value at least have to be filled, except if you set your own ```default_lang``` in your config file.

Each lang defined here will be used to determine the visibility of each element in the ```<CurriculumVitae>``` block.
That allow you to write all information in your curriculum vitae and after exposing the translation just when you want with the addition of the language in the ```<lang>``` block.

The following example allow to use the english and the french language in the route with the **en** and **fr** parameters, like ```/cv/example/en``` and ```/cv/example/fr```.

``` xml
<lang>
    <en>English</en>
    <fr>Français</fr>
</lang>
```

#### How to use

When a tag with a value is defined, add ```lang="en"``` as attribute will apply a filter for the language defined. If there is not this attribute then the tag value will appear for all languages.

For example:
``` xml
<valueAlwaysDisplay>First Name Last Name</valueAlwaysDisplay>
<valueDisplayForLangFR lang="fr">Paris</valueDisplayForLangFR>
<valueDisplayForLangEN lang="en">French Citizenship</valueDisplayForLangEN>
```


### CurriculumVitae Block

This block is the main part of your curriculum vitae, where you will write all about you ;)

All blocks inside will follow this structure:
``` xml
<CurriculumVitae>
    ...
    <oneblock anchor="anchor_id">
        <AnchorTitle lang="en">Block title</AnchorTitle>
        <AnchorTitle lang="fr">Titre du block</AnchorTitle>
        <items>
        </items>
    </oneblock>
    ...
</CurriculumVitae>
```


#### Identity block

The identity block allows to define you. There are different parts inside ```<items>``` tag.
``` xml
<CurriculumVitae>
    ...
    <identity anchor="identity">
        <AnchorTitle lang="en">About Me</AnchorTitle>
        <AnchorTitle lang="fr">A propos</AnchorTitle>
        <items>
        </items>
    </identity>
    ...
</CurriculumVitae>
```

##### Myself tag

```<myself>``` tag is to present you.
``` xml
<myself>
    <Name>Fabien Crassat</Name>
    <BirthDay format="mm/dd/yy">01/01/1981</BirthDay>
    <Age getAge="CurriculumVitae/identity/items/myself/BirthDay"></Age>
    <BrithPlace>Paris</BrithPlace>
    <Nationality lang="en">French Citizenship</Nationality>
    <Nationality lang="fr">Citoyen français</Nationality>
    <Picture>bundles/fabiencrassatcurriculumvitae/img/example.png</Picture>
</myself>
```

And the bundle have some restriction:

*   ```BirtDay``` tag accept only the format attribute "mm/dd/yy"
*   ```Picture``` tag is the link to the filesystem of ```web/``` directory, so it is necessary to expose your file inside ```Resources/public/img``` of your bundle and launch the ``` php app/console assets:install ``` command

##### Address tag

```<address>``` tag is to present where you live.
``` xml
<address>
    <City>Chicago</City>
    <Country lang="en">USA</Country>
    <Country lang="fr">Etat Unis</Country>
    <GoogleMap>http://maps.google.com</GoogleMap>
</address>
```

##### Contact tag

```<contact>``` tag is to present how contact you.
``` xml
<contact>
    <Email>email_arobase_site_dot_com</Email>
</contact>
```

##### Social tag

```<social>``` tag is to present the social references.
``` xml
<social>
    <Marital lang="fr">Célibataire</Marital>
    <Military lang="fr">Dégagé des obligations militaires</Military>
    <DriveLicences lang="en">French driving licence</DriveLicences>
    <DriveLicences lang="fr">Titulaire du permis B</DriveLicences>
</social>
```

#### FollowMe block

The FollowMe block presents site links. If you use [the export with Knp Snappy PDF](export_to_PDF.md), this block is not export.
``` xml
<CurriculumVitae>
    ...
    <followMe anchor="followMe">
        <AnchorTitle lang="en">Follow Me</AnchorTitle>
        <AnchorTitle lang="fr">Suivez-moi</AnchorTitle>
        <items>
        </items>
    </followMe>
    ...
</CurriculumVitae>
```

In the ```<items>```, the structure is defined below:
``` xml
<site>
    <title lang="en">Link Title</title>
    <title lang="fr">Titre du lien</title>
    <url>http://www.facebook.com</url>
    <icon>bundles/fabiencrassatcurriculumvitae/img/facebook.png</icon>
</site>
``` 

To help you, there are the following list of icons:

Icon            | Image                                                               | Property in icon tag
:---------------|:--------------------------------------------------------------------|:------------------------------------------------------------------------------
 facebook       | ![facebook](../public/img/facebook.png "facebook")                  | ```<icon>bundles/fabiencrassatcurriculumvitae/img/facebook.png</icon>```
 google+        | ![googleplus](../public/img/googleplus.png "googleplus")            | ```<icon>bundles/fabiencrassatcurriculumvitae/img/googleplus.png</icon>```
 linkedin       | ![linkedin](../public/img/linkedin.png "linkedin")                  | ```<icon>bundles/fabiencrassatcurriculumvitae/img/linkedin.png</icon>```
 monster        | ![monster](../public/img/monster.png "monster")                     | ```<icon>bundles/fabiencrassatcurriculumvitae/img/monster.png</icon>```
 scrum-alliance | ![scrum-alliance](../public/img/scrum-alliance.png "scrum-alliance")| ```<icon>bundles/fabiencrassatcurriculumvitae/img/scrum-alliance.png</icon>```
 twitter        | ![twitter](../public/img/twitter.png "twitter")                     | ```<icon>bundles/fabiencrassatcurriculumvitae/img/twitter.png</icon>```
 viadeo         | ![viadeo](../public/img/viadeo.png "viadeo")                        | ```<icon>bundles/fabiencrassatcurriculumvitae/img/viadeo.png</icon>```

#### LookingFor block

The LookingFor block presents the head of you curriculum vitae.
``` xml
<CurriculumVitae>
    ...
    <lookingFor>
    </lookingFor>
    ...
</CurriculumVitae>
```

In the ```<lookingFor>```, the structure is defined below:
``` xml
<experience lang="en">Curriculum Vitae Title</experience>
<experience lang="fr">Titre du curriculum vitae</experience>
<presentation lang="en">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean eu lectus facilisis, posuere leo laoreet, dignissim ligula. Praesent ultricies dignissim diam vitae dictum. Donec sed nisi tortor. Proin tempus scelerisque lectus, sit amet convallis mi semper a. Integer blandit a ligula a volutpat. Ut dolor eros, interdum quis ante ac, tempus commodo odio. Suspendisse ut nisi purus. Mauris vestibulum nibh sit amet turpis consequat pharetra. Duis at adipiscing risus. Vivamus vitae orci ac felis porta euismod. Fusce sit amet metus sem. Maecenas suscipit tincidunt ante, sed feugiat odio eleifend eu. Sed eu ultricies ipsum. In cursus tincidunt elit a gravida. Nam eu aliquet leo. Maecenas nibh leo, eleifend fermentum neque sit amet, viverra consequat lorem. </presentation>
<presentation lang="fr">Mauris rutrum justo ac bibendum ultrices. Mauris a dolor a diam tempus ornare vel non urna. Donec a dui vel nunc ultrices porta non vitae felis. Ut blandit ullamcorper orci. Quisque quis justo vitae nisl auctor laoreet non eget mauris. Sed volutpat enim est, vitae vulputate nibh laoreet gravida. Duis nec tincidunt ante. Nullam metus turpis, accumsan nec laoreet et, consectetur et ligula. Curabitur convallis feugiat lorem, sit amet tincidunt arcu sollicitudin vel. Aliquam erat volutpat. In odio elit, accumsan in facilisis at, ultricies quis justo. </presentation>
``` 

#### Experiences block

The Experiences block presents your experiences.
``` xml
<CurriculumVitae>
    ...
    <experiences anchor="experiences">
        <AnchorTitle lang="en">Experiences</AnchorTitle>
        <AnchorTitle lang="fr">Expériences Professionnelles</AnchorTitle>
        <items>
        </items>
    </experiences>
    ...
</CurriculumVitae>
```

In the ```<items>```, the structure is defined below:
``` xml
<OneExperience collapse="true">
    <date lang="en">Jan 2007 - Present</date>
    <date lang="fr">Jan. 2007 - Aujourd'hui</date>
    <job lang="en">My current job</job>
    <job lang="fr">Mon poste actuel</job>
    <society>The World Company</society>
    <missions lang="en">
        <item>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</item>
        <item>Suspendisse nec mauris eu orci dapibus mollis ac ac mi.</item>
    </missions>
    <missions lang="fr">
        <item>Donec gravida enim viverra tempor dignissim.</item>
        <item>Sed a eros at mauris placerat adipiscing.</item>
    </missions>
</OneExperience>
``` 

```collapse``` attribute is ```false``` by default.

#### Skills block

The Skills block presents your skills.
``` xml
<CurriculumVitae>
    ...
    <skills anchor="skills">
        <AnchorTitle lang="en">Skills</AnchorTitle>
        <AnchorTitle lang="fr">Compétences</AnchorTitle>
        <items>
        </items>
    </skills>
    ...
</CurriculumVitae>
```

In the ```<items>```, the structure is defined below:
``` xml
<oneSkill>
    <title lang="en">Skills</title>
    <title lang="fr">Compétences</title>
    <lines>
        <sucess percentage="90" class="success" striped="true">
            <label lang="en">Increasing Skills</label>
            <label lang="fr">Compétences grandissantes</label>
        </sucess>
        <otherSucess percentage="90" class="success">
            <label>sucess</label>
        </otherSucess>
        <info percentage="40" class="info">
            <label>info</label>
        </info>
        <warning percentage="20" class="warning">
            <label>warning</label>
        </warning>
        <danger percentage="10" class="danger" striped="false">
            <label>danger</label>
        </danger>
        <noClass percentage="5">
            <label>noClass</label>
        </noClass>
        <nothing>
            <label>nothing</label>
        </nothing>
    </lines>
</oneSkill>
``` 

```percentage``` attribute is ```0``` by default.
```class``` attribute is ```info``` by default.
```striped``` attribute is ```false``` by default.  

#### Education block

The Education block presents your educations.
``` xml
<CurriculumVitae>
    ...
    <educations anchor="educations">
        <AnchorTitle lang="en">Educations</AnchorTitle>
        <AnchorTitle lang="fr">Formations</AnchorTitle>
        <items>
        </items>
    </educations>
    ...
</CurriculumVitae>
```

In the ```<items>```, the structure is defined below:
``` xml
<OneSchool collapse="true">
    <date lang="en">June 2002</date>
    <date lang="fr">Juin 2002</date>
    <education lang="en">My diploma in my university</education>
    <education lang="fr">Mon diplôme dans mon université</education>
    <descriptions lang="en">
        <item>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc in auctor ipsum. Nullam venenatis sem.</item>
    </descriptions>
    <descriptions lang="fr">
        <item>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris elit dui, faucibus non laoreet luctus, dignissim at lectus. Quisque dignissim imperdiet consectetur. Praesent scelerisque neque.</item>
        <item>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed pretium varius est sit amet consectetur. Suspendisse cursus dapibus egestas. Ut id augue quis mi scelerisque.</item>
    </descriptions>
</OneSchool>
``` 

```collapse``` attribute is ```false``` by default.

#### LanguageSkills block

The LanguageSkills block presents your language level.
``` xml
<CurriculumVitae>
    ...
    <languageSkills anchor="languageSkills">
        <AnchorTitle lang="en">Languages</AnchorTitle>
        <AnchorTitle lang="fr">Langues</AnchorTitle>
        <items>
        </items>
    </languageSkills>
    ...
</CurriculumVitae>
```

In the ```<items>```, the structure is defined below:
``` xml
<French>
    <title lang="en">French</title>
    <title lang="fr">Français</title>
    <description lang="en">Level of the skill.</description>
    <description lang="fr">Niveau</description>
    <icon>bundles/fabiencrassatcurriculumvitae/img/Flag-of-France.png</icon>
</French>
``` 

To help you, there are the following list of icons:

Icon     | Image                                                          | Property in icon tag
:--------|:---------------------------------------------------------------|:----------------------------------------------------------------------------------
 French  | ![French](../public/img/Flag-of-France.png "French")           | ```<icon>bundles/fabiencrassatcurriculumvitae/img/Flag-of-France.png</icon>```
 English | ![English](../public/img/Flag-of-United-Kingdom.png "English") | ```<icon>bundles/fabiencrassatcurriculumvitae/img/Flag-of-United-Kingdom</icon>```

#### miscellaneous block

The miscellaneous block presents what you want ;).
``` xml
<CurriculumVitae>
    ...
    <miscellaneous anchor="miscellaneous">
        <AnchorTitle lang="en">Miscellaneous</AnchorTitle>
        <AnchorTitle lang="fr">Divers</AnchorTitle>
        <items>
        </items>
    </miscellaneous>
    ...
</CurriculumVitae>
```

In the ```<items>```, the structure is defined below:
``` xml
<Practical>
    <title lang="en">Practices</title>
    <title lang="fr">Pratiques</title>
    <miscellaneous lang="en">My practices</miscellaneous>
    <miscellaneous lang="fr">Mes pratiques</miscellaneous>
    <description lang="fr">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur nec auctor nisl, eu fringilla nisi. Morbi scelerisque, est vitae mattis faucibus, felis sapien lobortis augue.</description>
</Practical>
``` 

#### Society block

The Society block allows to define each society you have worked (are working).
Inside the block, you can declare all society you want like the following example. And the block **name** is one to be filled, but you can add the others tags to have more information.

``` xml
<Society>
    <MyCompany>
        <name>My Company</name>
        <address>The address of the company</address>
        <siteurl>http://www.MyCompany.com</siteurl>
    </MyCompany>
</Society>
```

To use one of societies in your curriculum vitae, you have to use the cross reference like this:
``` xml
<society crossref="Society/MyCompany"></society>
```
