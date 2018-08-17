# Using a custom Curriculum Vitae File

The structure of the Curriculum is not store in database, but in xml file.

You can see an example of all you can do with the [example.xml](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/data/example.xml "example.xml")

## Summarize

- [Summarize](#summarize)
- [Main Structure](#main-structure)
- [Block tag](#block-tag)
    - [Langs Block](#langs-block)
        - [Declaration](#declaration)
        - [How to use](#how-to-use)
    - [CurriculumVitae Block](#curriculumvitae-block)
        - [Identity block](#identity-block)
            - [Myself tag](#myself-tag)
            - [Address tag](#address-tag)
            - [Contact tag](#contact-tag)
            - [Social tag](#social-tag)
        - [FollowMe block](#followme-block)
        - [LookingFor block](#lookingfor-block)
        - [Experiences block](#experiences-block)
        - [Skills block](#skills-block)
        - [Education block](#education-block)
        - [LanguageSkills block](#languageskills-block)
        - [miscellaneous block](#miscellaneous-block)
        - [Society block](#society-block)

## Main Structure

The main structure you need to allow the personlization of the CV is:

``` xml
<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
<root>
    <langs>
    </langs>
    <curriculumVitae>
    </curriculumVitae>
</root>
```

## Block tag

### Langs Block

#### Declaration

It is all languages you want to expose in your curriculum vitae.

`<lang id="en">English</lang>` value at least have to be filled, except if you set your own `default_lang` in your config file.

Each lang defined here will be used to determine the visibility of each element in the `<curriculumVitae>` block.
That allow you to write all information in your curriculum vitae and after exposing the translation just when you want with the addition of the language in the `<langs>` block.

The following example allow to use the english and the french language in the route with the **en** and **fr** parameters, like `/cv/example/en` and `/cv/example/fr`.

``` xml
<langs>
    <lang id="en">English</lang>
    <lang id="fr">Français</lang>
</langs>
```

#### How to use

When a tag with a value is defined, add `lang="en"` as attribute will apply a filter for the language defined. If there is not this attribute then the tag value will appear for all languages.

For example:

``` xml
<valueAlwaysDisplay>Visible for all languages</valueAlwaysDisplay>
<valueDisplayForLangFR lang="fr">Visible seulement pour la langue française</valueDisplayForLangFR>
<valueDisplayForLangEN lang="en">Visible only for English language</valueDisplayForLangEN>
```

### CurriculumVitae Block

This block is the main part of your curriculum vitae, where you will write all about you ;)

All blocks inside will follow this structure, except [LookingFor block](#lookingfor-block "LookingFor block"):

``` xml
<curriculumVitae>
    ...
    <oneblock anchor="oneblock">
        <anchorTitle lang="en">Block title</anchorTitle>
        <anchorTitle lang="fr">Titre du block</anchorTitle>
        <items>
            ...
            tag here
            ...
        </items>
    </oneblock>
    ...
</curriculumVitae>
```

#### Identity block

The identity block allows to define you. There are different parts inside `<items>` tag.

``` xml
<curriculumVitae>
    ...
    <identity anchor="identity">
        <anchorTitle lang="en">About Me</anchorTitle>
        <anchorTitle lang="fr">A propos</anchorTitle>
        <items>
            ...
            tag here
            ...
        </items>
    </identity>
    ...
</curriculumVitae>
```

##### Myself tag

`<myself>` tag is to present you.

``` xml
<myself>
    <name>Fabien Crassat</name>
    <birthday>1981-12-31</birthday>
    <age getAge="curriculumVitae/identity/items/myself/birthday"></age>
    <birthplace>Paris</birthplace>
    <nationality lang="en">French Citizenship</nationality>
    <nationality lang="fr">Citoyen français</nationality>
    <picture>bundles/fabiencrassatcurriculumvitae/img/example.png</picture>
</myself>
```

And the following elements have some restriction:

- `birthday` tag accept only the format attribute "YYYY-mm-dd"
- `picture` tag is the link to the filesystem of `web/` directory, so it is necessary to expose your file inside `Resources/public/img` of your bundle and launch the `php app/console assets:install` command

##### Address tag

`<address>` tag is to present where you live.

``` xml
<address>
    <city>Chicago</city>
    <country lang="en">USA</country>
    <country lang="fr">Etat Unis</country>
    <googlemap>http://maps.google.com</googlemap>
</address>
```

##### Contact tag

`<contact>` tag is to present how contact you.

``` xml
<contact>
    <email>email_arobase_site_dot_com</email>
</contact>
```

##### Social tag

`<social>` tag is to present the social references.

``` xml
<social>
    <marital lang="fr">Célibataire</marital>
    <military lang="fr">Dégagé des obligations militaires</military>
    <drivelicences lang="en">French driving licence</drivelicences>
    <drivelicences lang="fr">Titulaire du permis B</drivelicences>
</social>
```

#### FollowMe block

The FollowMe block presents site links. If you use [an export PDF service](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/doc/export_to_PDF.md), this block is not export.

``` xml
<curriculumVitae>
    ...
    <followMe anchor="followMe">
        <anchorTitle lang="en">Follow Me</anchorTitle>
        <anchorTitle lang="fr">Suivez-moi</anchorTitle>
        <items>
            ...
            tag here
            ...
        </items>
    </followMe>
    ...
</curriculumVitae>
```

In the `<items>`, the structure is defined below:

``` xml
<followItem id="site">
    <title lang="en">Link Title</title>
    <title lang="fr">Titre du lien</title>
    <url>http://www.thesite.com</url>
    <icon>bundles/fabiencrassatcurriculumvitae/img/icon.png</icon>
</followItem>
```

To help you, there are the following list of icons:

Icon            | Image                                                                                                                                         | Property in icon element
:---------------|:----------------------------------------------------------------------------------------------------------------------------------------------|:------------------------------------------------------------------------------
 facebook       | ![facebook](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/public/img/facebook.png "facebook")                  | `bundles/fabiencrassatcurriculumvitae/img/facebook.png`
 google+        | ![googleplus](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/public/img/googleplus.png "googleplus")            | `bundles/fabiencrassatcurriculumvitae/img/googleplus.png`
 linkedin       | ![linkedin](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/public/img/linkedin.png "linkedin")                  | `bundles/fabiencrassatcurriculumvitae/img/linkedin.png`
 monster        | ![monster](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/public/img/monster.png "monster")                     | `bundles/fabiencrassatcurriculumvitae/img/monster.png`
 scrum-alliance | ![scrum-alliance](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/public/img/scrum-alliance.png "scrum-alliance")| `bundles/fabiencrassatcurriculumvitae/img/scrum-alliance.png`
 twitter        | ![twitter](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/public/img/twitter.png "twitter")                     | `bundles/fabiencrassatcurriculumvitae/img/twitter.png`
 viadeo         | ![viadeo](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/public/img/viadeo.png "viadeo")                        | `bundles/fabiencrassatcurriculumvitae/img/viadeo.png`

#### LookingFor block

The LookingFor block presents the head of you curriculum vitae.

``` xml
<curriculumVitae>
    ...
    <lookingFor>
    </lookingFor>
    ...
</curriculumVitae>
```

In the `<lookingFor>`, the structure is defined below:

``` xml
<experience lang="en">Curriculum Vitae Title</experience>
<experience lang="fr">Titre du curriculum vitae</experience>
<presentation lang="en">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean eu lectus facilisis, posuere leo laoreet, dignissim ligula.</presentation>
<presentation lang="fr">Mauris rutrum justo ac bibendum ultrices. Mauris a dolor a diam tempus ornare vel non urna.</presentation>
```

#### Experiences block

The Experiences block presents your experiences.

``` xml
<curriculumVitae>
    ...
    <experiences anchor="experiences">
        <anchorTitle lang="en">Experiences</anchorTitle>
        <anchorTitle lang="fr">Expériences Professionnelles</anchorTitle>
        <items>
            ...
            tag here
            ...
        </items>
    </experiences>
    ...
</curriculumVitae>
```

In the `<items>`, the structure is defined below:

``` xml
<experience id="OneExperience" collapse="true">
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
</experience>
```

For each experience, you can add attributes to have more visual effects.

- `collapse` attribute (`false` by default) will collapse the experience to hide it and let your visitor the choice to show it or not. It is useful when you want to hide some no-interesting informations.

#### Skills block

The Skills block presents your skills.

``` xml
<curriculumVitae>
    ...
    <skills anchor="skills">
        <anchorTitle lang="en">Skills</anchorTitle>
        <anchorTitle lang="fr">Compétences</anchorTitle>
        <items>
            ...
            tag here
            ...
        </items>
    </skills>
    ...
</curriculumVitae>
```

In the `<items>`, the structure is defined below:

``` xml
<skill id="oneSkill">
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
</skill>
```

For each line, you can add attributes to have more visual effects.

- `percentage` attribute (`0` by default) can show how you know the skill.
- `class` attribute (`info` by default) can show how you know the skill.
- `striped` attribute (`false` by default) changes the visual of the line.

#### Education block

The Education block presents your educations.

``` xml
<curriculumVitae>
    ...
    <educations anchor="educations">
        <anchorTitle lang="en">Educations</anchorTitle>
        <anchorTitle lang="fr">Formations</anchorTitle>
        <items>
            ...
            tag here
            ...
        </items>
    </educations>
    ...
</curriculumVitae>
```

In the `<items>`, the structure is defined below:

``` xml
<education id="OneSchool" collapse="true">
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
</education>
```

For each education, you can add attributes to have more visual effects.

- `collapse` attribute (`false` by default) will collapse the education to hide it and let your visitor the choice to show it or not. It is useful when you want to hide some no-interesting informations.

#### LanguageSkills block

The LanguageSkills block presents your language level.

``` xml
<curriculumVitae>
    ...
    <languageSkills anchor="languageSkills">
        <anchorTitle lang="en">Languages</anchorTitle>
        <anchorTitle lang="fr">Langues</anchorTitle>
        <items>
            ...
            tag here
            ...
        </items>
    </languageSkills>
    ...
</curriculumVitae>
```

In the `<items>`, the structure is defined below:

``` xml
<languageSkill id="French">
    <title lang="en">French</title>
    <title lang="fr">Français</title>
    <description lang="en">Level of the skill.</description>
    <description lang="fr">Niveau</description>
    <icon>bundles/fabiencrassatcurriculumvitae/img/Flag-of-France.png</icon>
</languageSkill>
```

To help you, there are the following list of icons:

Icon     | Image                                                                                                                                    | Property in icon tag
:--------|:-----------------------------------------------------------------------------------------------------------------------------------------|:----------------------------------------------------------------------------------
 French  | ![French](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/public/img/Flag-of-France.png "French")           | `bundles/fabiencrassatcurriculumvitae/img/Flag-of-France.png`
 English | ![English](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/public/img/Flag-of-United-Kingdom.png "English") | `bundles/fabiencrassatcurriculumvitae/img/Flag-of-United-Kingdom`

#### miscellaneous block

The miscellaneous block presents what you want ;).

``` xml
<curriculumVitae>
    ...
    <miscellaneous anchor="miscellaneous">
        <anchorTitle lang="en">Miscellaneous</anchorTitle>
        <anchorTitle lang="fr">Divers</anchorTitle>
        <items>
            ...
            tag here
            ...
        </items>
    </miscellaneous>
    ...
</curriculumVitae>
```

In the `<items>`, the structure is defined below:

``` xml
<miscellaneous id="Practical">
    <title lang="en">Practices</title>
    <title lang="fr">Pratiques</title>
    <miscellaneous lang="en">My practices</miscellaneous>
    <miscellaneous lang="fr">Mes pratiques</miscellaneous>
    <description lang="fr">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</description>
</miscellaneous>
```

#### Society block

The Society block allows to define each society you have worked (are working).
Inside the block, you can declare all society you want like the following example. And the block `name` is one to be filled, but you can add the others tags to have more information.

``` xml
<societies>
    <society ref="MyCompany">
        <name>My Company</name>
        <address>The address of the company</address>
        <siteurl>http://www.MyCompany.com</siteurl>
    </society>
</societies>
```

To use one of societies in your curriculum vitae, you have to use the cross reference like this:

``` xml
<society crossref="societies/society[@ref='MyCompany']/*"></society>
```
