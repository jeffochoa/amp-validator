<p align="center">
    <img src="https://raw.githubusercontent.com/jeffochoa/amp-validator/master/docs/example.png" alt="AMP validation Example" height="300">
</p>

<p align="center">
  <a href="https://packagist.org/packages/jeffochoa/amp-validator"><img src="https://poser.pugx.org/jeffochoa/amp-validator/d/total.svg" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/jeffochoa/amp-validator"><img src="https://poser.pugx.org/jeffochoa/amp-validator/v/stable.svg" alt="Latest Version"></a>
  <a href="https://packagist.org/packages/jeffochoa/amp-validator"><img src="https://poser.pugx.org/jeffochoa/amp-validator/license.svg" alt="License"></a>
</p>

------

## About AMP validation CLI tool

Test your Accelerated Mobile Pages right away from your terminal.

[![AMP online validation tool](https://raw.githubusercontent.com/jeffochoa/amp-validator/master/docs/validation-video.jpg)](https://www.ampproject.org/docs/fundamentals/validate)

This package uses CLOUDFARE's [AMP validator API](https://blog.cloudflare.com/amp-validator-api/).

## Installation

### Via Composer

```bash
composer global require jeffochoa/amp-validator
```

### Manually

First, download the binary using `wget`:

```bash
wget https://github.com/jeffochoa/amp-validator/blob/master/builds/amp-validator -O amp-validator
```

Change binary permissions:

```bash
sudo chmod ax amp-validator
```

Move to bin directory:

```bash
sudo mv amp-validator /usr/local/bin/amp-validator
```

## Usage

```bash
amp-validator
```

### Singe page validation

The given URL should be publicly accessible.

```bash
amp-validator validate http://website.test/valid-amp-link
```

### Validate a batch of links

You can use the generated CSV report by [Google Webmaster Tools](https://www.google.com/webmasters/tools/home) (GWT) as input to validate locally.

[![AMP online validation tool](https://raw.githubusercontent.com/jeffochoa/amp-validator/master/docs/csv-download.png)](https://www.google.com/webmasters/tools/accelerated-mobile-pages)

Go to [https://www.google.com/webmasters/tools/accelerated-mobile-pages](https://www.google.com/webmasters/tools/accelerated-mobile-pages)

Download the AMP report in `Search Appearance / Accelerate mobile pages`

Once you have the file downloaded locally:

```bash
amp-validator validate-batch path-to-file/downloaded.csv
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

## License

AMP Validator is an open-sourced software licensed under the [MIT license](LICENSE.md).
