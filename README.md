### Intro

Chevron aims to be a 'simple set of useful tools'. This means that it
exists as a collection of code I've written and found quite useful. Over
time I hope it grows in scope and usefulness but not in complexity.

As it stands now, there is a PDO wrapper with some useful helper
functions, a dependency injection container, a simple input sanitizer,
and a basic implementation of the registry pattern, and a few more.

### Why?

Because I find these tools useful and wanted to package them in a way
that I could reuse them. Most of these tools have made building web
applications much easier for me and I hope, if you download them, you
find them useful as well.

### Goals

I really like The Zen of Python. I try to emulate *most* of them *most*
of the time.

### How would one go about using these tools?

Check the tests. Hopefully they're verbose enough to double as examples
and documentation.

### Installation (Composer)

	"require": { "chevron/chevron": "dev-master" }

### TODO

  - cleanup, finish, more betterify the comments and documentation
  - rewrite and test Chevron\Redis
  - test Chevron\Stub
  - write some example usage

### Notes

  - Don't use the included autoloader, you should use Composers.

### Travis

[![Build Status](https://travis-ci.org/henderjon/chevron.png)](https://travis-ci.org/henderjon/chevron)


