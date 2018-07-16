# AMP validation CLI tool

Test your Accelerated Mobile Pages right away from your terminal.

[![AMP online validation tool](https://raw.githubusercontent.com/jeffochoa/amp-validator/master/docs/validation-video.jpg)](https://www.ampproject.org/docs/fundamentals/validate)

This package uses CLOUDFARE's [AMP validator API](https://blog.cloudflare.com/amp-validator-api/).

## Install

### Download or clone (Local development)

You can download the package locally:

```bash
git clone git@github.com:jeffochoa/amp-validator.git
```

Then install all the dependencies using composer:

```bash
composer install
```

Finally you can use the validation tool from the package root folder

```bash
  php amp

  Amp-tester  unreleased

  USAGE: amp <command> [options] [arguments]

  validate       Test a single AMP url.
  validate-batch Validate a batch of AMP pages from a CSV file.

  app:build      Compile your application into a single file
  app:install    Installs a new component
  app:rename     Change the application name

  make:command   Create a new command
```

## Global install (Composer)

You can also install this CLI tool globally using composer.

```bash
composer global require jeffochoa/amp-validator
```

Make sure you have the global Composer binaries directory in your PATH. This directory may change depending on the platform you are.

On ubuntu:

```bash
export PATH="$PATH:$HOME/.composer/vendor/bin"
```

## Install using binary file

First, you'll need to download the binary file

```bash
wget https://github.com/jeffochoa/amp-validator/blob/master/builds/amp.phar -O amp
```

Change file permissions

```bash
sudo chmod ax amp
```

Move to bin directory

```bash
sudo mv amp /usr/local/bin/amp
```

---

## Usage

### Singe page validation

The given URL should be publicly accessible.

```bash
php amp validate http://website.test/valid-amp-link
```

### Validate a batch of links

You can use the generated CSV report by [Google Webmaster Tools](https://www.google.com/webmasters/tools/home) (GWT) as input to validate locally.

[![AMP online validation tool](https://raw.githubusercontent.com/jeffochoa/amp-validator/master/docs/csv-download.png)](https://www.google.com/webmasters/tools/accelerated-mobile-pages)

Go to [https://www.google.com/webmasters/tools/accelerated-mobile-pages](https://www.google.com/webmasters/tools/accelerated-mobile-pages)

Download the AMP report in `Search Appearance / Accelerate mobile pages`

Once you have the file downloaded locally:

```bash
php amp validate-batch path-to-file/downloaded.csv
```

This tool will read the CSV file generated on GWT to run the validation on each tests contained in the file, then you can select between the different types of output formats to export your report.

```text

    Please select an output format
    ----------------------------------------------------------------------------------

    ● Export to CSV file
    ○ In console (summarized)
    ○ In console (extended)
    ○ Cancel
```

#### Extra help to fix the errors in your page
The following example is the output generated using the "In console (extended)" option:

```text
-----------------------------------------------------------------------------------
| Key     | Value                                                                    |
-----------------------------------------------------------------------------------
| link    | https://you-given-url.test                                               |
| error   | The attribute 'target' in tag 'a' is set to the invalid value 'blank'.   |
| line    | 1221                                                                     |
| col     | 3                                                                        |
| code    | INVALID_ATTR_VALUE                                                       |
| help    | https://www.ampproject.org/docs/reference/spec#links                     |
| preview | https://search.google.com/test/amp?url=https://you-given-url.test        |
------------------------------------------------------------------------------------
```

If you click on the `preview` link, you'll be taken to the online google validation tool.

![AMP online validation tool](https://raw.githubusercontent.com/jeffochoa/amp-validator/master/docs/test-online.jpg)