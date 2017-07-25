kodus/jpeg
==========

Wrapper around [jpeg-recompress](https://github.com/danielgtaylor/jpeg-archive) for Linux/Mac/Windows.

Includes a bundled [release 2.1.1](https://github.com/danielgtaylor/jpeg-archive/releases/tag/2.1.1) of the
[JPEG-Archive](https://github.com/danielgtaylor/jpeg-archive) binaries as provided
by [Daniel G. Taylor](https://github.com/danielgtaylor).


### Setup

You may need to [set permissions](https://symfony.com/doc/current/setup/file_permissions.html)
in order to run the built-in binaries.

Run the test-suite to see if the built-in defaults make sense for your system:

    php test/test.php


### Usage

```php
$service = new JPEGService();

$service->compress("input.jpg", "output.jpg");
```

Use the optional constructor arguments to specify a custom binary path (in case none of the
built-in binaries work for you) as well as customizing the command-line arguments.
