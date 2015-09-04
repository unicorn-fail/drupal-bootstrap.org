# CommonMark

This module provides a text format filter that converts Markdown to HTML based
on the [CommonMark] spec via [thephpleague/commonmark] PHP library, created and
maintained by [The League of Extraordinary Packages].

## Requirements
The CommonMark module requires the [Composer Manager] module to install the
necessary PHP library and leverage PSR-4 autoloading capabilities.

## Supported CommonMark Extensions
* [CommonMark Attributes Extension]  
  Adds syntax to define attributes on various HTML elements inside a CommonMark
  markdown document. To install, go to the root composer project and run:  
  `composer require webuni/commonmark-attributes-extension:~0.2.0`
* [CommonMark Table Extension]  
  Adds syntax to create tables in a CommonMark markdown document. To install,
  go to the root composer project and run:  
  `composer require webuni/commonmark-table-extension:~0.3.0`

[CommonMark]: http://commonmark.org/
[CommonMark Attributes Extension]: https://github.com/webuni/commonmark-attributes-extension
[CommonMark Table Extension]: https://github.com/webuni/commonmark-table-extension
[Composer Manager]: https://www.drupal.org/project/composer_manager
[thephpleague/commonmark]: https://github.com/thephpleague/commonmark
[The League of Extraordinary Packages]: http://commonmark.thephpleague.com/
