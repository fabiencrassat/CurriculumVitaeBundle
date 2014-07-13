# Using a custom Curriculum Vitae File

The structure of the Curriculum is not store in database, but in xml file.

You can see an example of all you can do with the [example.xml](https://github.com/FabienCrassat/CurriculumVitaeBundle/blob/master/Resources/data/example.xml "example.xml")

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
        *   [FollowMe block](#followme-block "FollowMe block") [todo]
        *   [LookingFor block](#lookingfor-block "LookingFor block") [todo]
        *   [Experiences block](#experiences-block "Experiences block") [todo]
        *   [Skills block](#skills-block "Skills block") [todo]
        *   [Education block](#education-block "Education block") [todo]
        *   [LanguageSkills block](#languageskills-block "LanguageSkills block") [todo]
        *   [Miscellaneous block](#miscellaneous-block "Miscellaneous block") [todo]
        *   [Society block](#society-block "Society block")

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

The identity block allows to define you. It is with different parts with inside some tags.
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

```<myself>``` tag is to present you and will be written inside ```<item>``` tag
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

```<address>``` tag is to present where you live and will be written inside ```<item>``` tag
``` xml
<address>
    <City>Chicago</City>
    <Country lang="en">USA</Country>
    <Country lang="fr">Etat Unis</Country>
    <GoogleMap>http://maps.google.com</GoogleMap>
</address>
```

##### Contact tag

```<contact>``` tag is to present how contact you and will be written inside ```<item>``` tag
``` xml
<contact>
    <Email>email_arobase_site_dot_com</Email>
</contact>
```

##### Social tag

```<social>``` tag is to present the social references and will be written inside ```<item>``` tag
``` xml
<social>
    <Marital lang="fr">Célibataire</Marital>
    <Military lang="fr">Dégagé des obligations militaires</Military>
    <DriveLicences lang="en">French driving licence</DriveLicences>
    <DriveLicences lang="fr">Titulaire du permis B</DriveLicences>
</social>
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
