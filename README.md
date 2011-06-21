
Box UK - Coding Standards
-------------------------

This repository contains coding standards for various languages.  Details about
how to use the standard for each language is provided below.  There is Phing
(http://phing.info) build file which runs tests for all the standards.

<pre>
%> phing
</pre>

PHP
---

The PHP standard is written for PHP_CodeSniffer (http://pear.php.net/package/PHP_CodeSniffer)
and can be run by using the *--standard* switch to _phpcs_.

<pre>
%> phpcs /my/code/php --standard=/path/to/boxuk-codingstandards/PHP/BoxUK
</pre>
