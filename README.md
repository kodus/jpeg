kodus/jpeg
==========

Wrapper around [jpeg-recompress](https://github.com/danielgtaylor/jpeg-archive) for Linux/Mac/Windows.

Includes a bundled [release 2.1.1](https://github.com/danielgtaylor/jpeg-archive/releases/tag/2.1.1) of the
[JPEG-Archive](https://github.com/danielgtaylor/jpeg-archive) binaries as provided
by [Daniel G. Taylor](https://github.com/danielgtaylor).


### Usage

```php
$service = new JPEGService();

$service->compress("input.jpg", "output.jpg");
```

Use the optional constructor arguments to specify a custom binary path (in case none of the
built-in binaries work for you) as well as customizing the command-line arguments.
