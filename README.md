# Broken Links Tool for QA purposes


[![pipeline status](https://git.alleo.tech/alt-projects/alt/altdev/broken-link-checker/badges/master/pipeline.svg)](https://git.alleo.tech/alt-projects/alt/altdev/broken-link-checker/-/commits/master)
[![coverage report](https://git.alleo.tech/alt-projects/alt/altdev/broken-link-checker/badges/master/coverage.svg)](https://git.alleo.tech/alt-projects/alt/altdev/broken-link-checker/-/commits/master)

##  Setup

```bash
$ docker build -t blt-centos:latest .
```

## Development

```bash
# Run container and keep it alive to apply changes
$ docker run --rm -it --entrypoint bash --mount type=bind,source="$(pwd)",target=/app blt-centos:latest 
# Within container
$ bin/broken-links-tool https://example.com
```

## Tests

```bash
$ docker run --rm -it --entrypoint bash --mount type=bind,source="$(pwd)",target=/app blt-centos:latest 
$ bin/composer test
```

## Create container and Run

```bash
$ docker run --rm blt-centos:latest https://example.com
```


## Flags

```bash
$ docker run --rm blt-centos:latest https://example.com --enable-js
# For JavaScript generated pages, defaults to false

$ docker run --rm blt-centos:latest https://example.com --invalid-urls
# Print invalid URLs only

$ docker run --rm blt-centos:latest https://example.com --valid-urls
# Print valid URLs only

# By default prints all URLs 
```

### Uncrawlable HTTP Code

Code: 1000
Reason: Failed to crawl

Usually, it is seen for sites not accepting web crawler or require user authorization

