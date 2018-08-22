# Expose you custom Curriculum Vitae Files

## Configuration

The bundle comes with a configuration, which is listed below.
So if you want to customized the bundle to call your CVs you have to define this configuration.

* ```path_to_cv```  
  Defines the path where the bundle grabs the curriculum vitae xml files
* ```custo_default_cv```  
  It is the default curriculum vitae xml file called without route
* ```default_lang```  
  It is the default curriculum vitae language
* ```template```  
  Defines your own twig template for you curriculum vitae

For example:

``` yml
# config/packages/fabiencrassat_curriculumvitae.yaml
fabien_crassat_curriculum_vitae:
    path_to_cv:       "%kernel.root_dir%/resources/curriculumvitae"
    custo_default_cv: "mycv"
    default_lang:     "fr"
    template:         "CV/index.html.twig"
```

In this example, the bundle will seek all curriculum vitae xml files inside the directory ```resources\curriculumvitae``` of your application. And by default it will call ```mycv.xml``` file for the language ```fr```. The render will be the ```templates\CV\index.html.twig``` file in your application

## Call your CV

After the configuration, you can display your CV like `http://localhost:8000/cv` or `http://localhost:8000/cv/mycv` or `http://localhost:8000/cv/mycv/fr`.

If you have other files in the exposed directory, you have just to name the file in the route: `http://localhost:8000/cv/an_other_cv` will grab `an_other_cv.xml` file.

Don't forget to [custom your curriculum vitae](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/doc/custom_cv_file.md) if you do not want an error 500 ;)
