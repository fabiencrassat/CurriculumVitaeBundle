# Using a custom Curriculum Vitae File

The structure of the Curriculum is not store in database, but in xml file.

You can see an example of all you can do with the [example.xml][1]
[1]: https://github.com/nimbusletruand/CurriculumVitaeBundle/blob/master/Resources/data/example.xml

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

### lang tag

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

### Society tag

The Society tag allows the cross reference in the curriculum vitae