# PhpCsv
A developing csv r/w library which act like a DB ORM

## Introduction
This is a developing project.

If you have a csv file like that
```
id,key,value
# data 1
1,aaa,b
```
We could think this csv as a table of a database. So this project is to manuplate the csv by some ORM function just like you are using the database. We hope that the function name is like the SQL identifier name so that you could learn it without any extra effort.

Now I have finish the reading part and support some sql function.

Usually, one php csv object is mapping for a csv file. We could make it as a model file. Likes others MVC frameworks, you could make a lot of model files for all csv file in order to manage them easily.

In this library, I design a Model structure for their purpose. If a csv file is call foo.csv, the model structure will look like this
```
[Foos]
  FooModel.php
  FooRepository.php
  FooValidator.php
```
FooModel.php mainly control model parameter.
FooRepository.php could put some data filter functions.
FooValidator.php is for validation function.

By split one model to three ones, the model could be looks lightweight and only load essetial parts when needs.

## Usage
Make sure you have include the library correctly.

1. Create model object (file name is foo.csv)
```
$foo = new FooModel("foo")
```

2. Select rows
The ORM is created by builder pattern. Currently support 'select', 'where', 'min', 'max', 'first', 'last', 'limit' key words
```
$foo->select()->where('id', '>=', 1)->where('id', '<=', 10)->done();
```

3. Access Repository and Validator
```
$foo->validator();
$foo->repository();
```

## License
Released under the [MIT license](http://www.opensource.org/licenses/MIT).



