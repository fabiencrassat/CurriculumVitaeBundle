# Understand the link beetween xml file and twig variables

Twig objects return by the controller:
```php
'cvxmlfile'      => string,
'languageView'   => string,
'hasSnappyPDF'   => boolean,
'languages'      => array(...),
'anchors'        => array(...),
'identity'       => array(...),
'followMe'       => array(...),
'lookingFor'     => array(...),
'experiences'    => array(...),
'skills'         => array(...),
'educations'     => array(...),
'languageSkills' => array(...),
'miscellaneous'  => array(...)
```

## cvxmlfile
It is your curriculum vitae file name, e.g. ```Mycv``` for ```mycv.xml```.

## languageView
It is the ```_locale``` variable use in the url, e.g. ```fr```

## hasSnappyPDF
Tell if you have installed [SnappyPDF](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/doc/export_to_PDF.md)

## languages
```array('en' => 'English', 'fr' => 'Français')```

## anchors
```php
array(
    'identity' => array (
        'href' => 'identity',
        'title' => 'About Me'
    ),
    'followMe' => array (
        'href' => 'followMe',
        'title' => 'Follow Me'
    ),
    'experiences' => array (
        'href' => 'experiences',
        'title' => 'Experiences'
    ),
    'skills' => array (
        'href' => 'skills',
        'title' => 'Skills'
    ),
    'educations' => array (
        'href' => 'educations',
        'title' => 'Educations'
    ),
    'languageSkills' => array (
        'href' => 'languageSkills',
        'title' => 'Languages'
    ),
    'miscellaneous' => array (
        'href' => 'miscellaneous',
        'title' => 'Miscellaneous'
    )
)
```

## identity
```php
array(
    'myself' => array (
        'name' => 'First Name Last Name',
        'birthday' => '01 janvier 1975',
        'age' => 39,
        'birthplace' => 'Paris',
        'nationality' => 'French Citizenship',
        'picture' => 'bundles/fabiencrassatcurriculumvitae/img/example.png'
    ),
    'address' => array (
        'street' => 'Street',
        'postalcode' => 'PostalCode',
        'city' => 'City',
        'country' => 'Country'
        'googlemap' => 'http://maps.google.com'
    ),
    'contact' => array (
        'mobile' => 'Telephone',
        'email' => 'email_arobase_site_dot_com'
    ),
    'array' => array (
        'marital' => 'Célibataire',
        'military' => 'Dégagé des obligations militaires',
        'drivelicences' => 'Titulaire du permis B'
    )
)
```

## followMe
```php
array(
    'linkedin' => array (
        'title' => 'Linked In',
        'url' => 'http://www.linkedin.com',
        'icon' => 'bundles/fabiencrassatcurriculumvitae/img/linkedin.png'
    ),
    ...
)
```

## lookingFor
```php
array(
    'experience' => 'Curriculum Vitae Title',
    'presentation' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
)
```

## experiences
```php
array(
    'FirstExperience' => array(
        'collapse' => 'false'
        'date' => 'Jan 2007 - Present',
        'job' => 'My current job',
        'society' => 'My society',
        'missions' => array(
            'item' => array(
                0 => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                1 => 'Suspendisse nec mauris eu orci dapibus mollis ac ac mi.',
                ...
            )
        )
    ),
    ...
)
```

## skills
```php
array(
    'OneSkill' => array(
        'title' => 'One skill'
        'lines' =>  array(
            'sucess' => array(
                'percentage' => '90',
                'class' => 'success',
                'striped' => 'false',
                'label' => 'Skills List'
            ),
            'info' => array(
                ...
            ),
            'warning' => array(
                ...
            ),
            'danger' => array(
                ...
            )
        )
    ),
    ...
)
```

## educations
```php
array(
    'University' => array(
        'collapse' => 'false'
        'date' => '2002 - 2005'
        'education' => 'My diploma in my university'
        'descriptions' =>  array(
            'item' => array(
                0 => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc in auctor ipsum.',
                1 => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc in auctor ipsum.',
                ...
            )
        )
    ),
    ...
)
```

## languageSkills
```php
array(
    'English' => array(
        'title' => 'English',
        'description' => 'Level of the skill.',
        'icon' => 'bundles/fabiencrassatcurriculumvitae/img/Flag-of-United-Kingdom.png',
    ),
    ...
)
```

## miscellaneous
```php
array(
    'Practical' => array(
        'title' => 'Practices',
        'miscellaneous' => 'My practices',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
    ),
    ...
)
```
