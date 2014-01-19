# Expose you custom Curriculum Vitae Files

## Configuration

The bundle comes with a configuration, which is listed below.
So if you want to customized the bundle to call your CVs you have to define this configuration.

*   path_to_cv
    Define here your directory where there are the curriculum vitae xml files inside
*   custo_default_cv
    It is the default curriculum vitae xml file called with this route: /cv

For example:
``` yml
# app/config/config.yml
nimbusletruand_curriculum_vitae:
    path_to_cv:       "Acme\Bundle\HelloBundle\Resources\curriculumvitae"
    custo_default_cv: "mycv"
```
In this example, the bundle will seek all curriculum vitae xml files inside the directory Resources\curriculumvitae in your bundle. And by default it will call mycv.xml file.