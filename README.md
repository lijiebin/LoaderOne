# LoaderOne
A better PHP class file autoloader library than PSR.

## Why LoaderOne
- PSR Specifications are so badly! Totally can't as a standard even or a suggestion because it too loosely and personalized customization.
- For the first reason, `Composer` is terrible too, it makes the PHP world messing.
- Conveniently to using and simply, scientific rules.
- Also good performance. 

## Usage
Download the `LoaderOne` source code floder placed it in your project dir anywhere, commonly at project root dir is ok.

## Required
PHP 5.3+

## Specification
1  Keep the "Namespace" completely mapping the actual directory's hierarchy.

2. Keep the "Class" and "Namespace" naming & capitalized same as the located directories name.

## Examples

|  FULLY QUALIFIED CLASS  |    PROJRCT ROOT     | FIND_PATH |                     RESULTING FILE PATH                     |
|------------------------------|---------------------|-----------|-------------------------------------------------------------|
| \Acme\Log\Writer\File_Writer | \var\www\html\atest | Vendors   | \var\www\html\atest\Vendors\Acme\Log\Writer\File_Writer.php |
| \Aura\Web\Response\Status    | \var\www\html\atest | Vendors   | \var\www\html\atest\Vendors\Aura\Web\Response\Status.php    |
| \Symfony\Core\Request        | \var\www\html\atest | Library   |  \var\www\html\atest\Library\Symfony\Core\Request.php       |
| \Zend\Acl                    | \var\www\html\atest | Library   | \var\www\html\atest\Library\Zend\Acl.php                    |

