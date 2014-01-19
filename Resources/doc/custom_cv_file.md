# Using a custom Curriculum Vitae File

The structure of the Curriculum is not store in database, but in xml file.

You can see an example of all you can do with the [example.xml](https://github.com/nimbusletruand/CurriculumVitaeBundle/blob/master/Resources/data/example.xml "example.xml")

## Summarize
*   [Custom Structure](#custom-structure "Custom Structure")
    *   [Lang tag](#lang-tag "Lang tag")
    *   [Identity tag](#identity-tag "Identity tag") [todo]
    *   [FollowMe tag](#followme-tag "FollowMe tag") [todo]
    *   [LookingFor tag](#lookingfor-tag "LookingFor tag") [todo]
    *   [Experiences tag](#experiences-tag "Experiences tag") [todo]
    *   [Skills tag](#skills-tag "Skills tag") [todo]
    *   [Education tag](#education-tag "Education tag") [todo]
    *   [LanguageSkills tag](#languageskills-tag "LanguageSkills tag") [todo]
    *   [Miscellaneous tag](#miscellaneous-tag "Miscellaneous tag") [todo]
    *   [Society tag](#society-tag "Society tag")

## Custom Structure

The main structure you need to allow the personlization of the twig template is:

``` xml
<xml version="1.0" encoding="UTF-8">
<lang>
</lang>
<CurriculumVitae>
    <identity>
        <items>
        </items>
    </identity>
    <followMe>
        <items>
        </items>
    </followMe>
    <lookingFor>
    </lookingFor>
    <experiences>
        <items>
        </items>
    </experiences>
    <skills>
        <items>
        </items>
    </skills>
    <educations>
        <items>
        </items>
    </educations>
    <languageSkills>
        <items>
        </items>
    </languageSkills>
    <miscellaneous>
        <items>
        </items>
    </miscellaneous>
</CurriculumVitae>
<Society>
</Society>
</xml>
```

### Lang tag

It is all languages you want to expose in your curriculum vitae. **One value at least** have to be filled, so choose your language ;)  
Each lang defined here will be used to determine the visibility of each element in the **CurriculumVitae** tag.
That allow you to write all information in your curriculum vitae and after exposing the translation just when you want with the addition of the language in the **lang** tag.

The following example allow to use the english and the french language in the route with the **en** and **fr** tag, like */cv/test/en* and */cv/test/fr*.

``` xml
<lang>
    <en>English</en>
    <fr>Français</fr>
</lang>
```

### Identity tag

The identity tag allows to define you. It is with different parts with inside some other tag.

#### Myself tag
Myself tag is to present you.
``` xml
<myself>
    <Name>First Name Last Name</Name>
    <BirthDay format="mm/dd/yy">01/01/1975</BirthDay>
    <Age lang="en" getAge="CurriculumVitae/identity/items/myself/BirthDay"></Age>
    <BrithPlace lang="fr">Paris</BrithPlace>
    <Nationality lang="en">French Citizenship</Nationality>
    <Picture>bundles/nimbusletruandcurriculumvitae/img/example.png</Picture>
</myself>
```
At this moment, the bundle have some restriction:
*   ```BirtDay``` tag accept only the format "mm/dd/yy"
*   ```Picture``` tag is the link to the filesystem of ```web/``` directory,  
    so it is necessary to expose your file inside ```Resources/public/img``` of your bundle  
    and launch the ``` php app/console assets:install ``` command

#### Address tag

#### contact tag

#### Social tag

### Society tag

The Society tag allows to define each society you have worked (are working).
Inside the tag, you can declare all society you want like the following example. And the tag **name** is one to be filled, but you can add the others tags to have more information.

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
See the [Experiences tag](#experiences-tag "Experiences tag") to more information.