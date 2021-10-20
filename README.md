# Broken Links Tool for QA purposes

[![pipeline status](https://git.alleo.tech/alt-projects/tff/tffict/threefold.io/badges/master/pipeline.svg)](https://git.alleo.tech/alt-projects/tff/tffict/threefold.io/-/commits/master)
[![coverage report](https://git.alleo.tech/alt-projects/tff/tffict/threefold.io/badges/master/coverage.svg)](https://git.alleo.tech/alt-projects/tff/tffict/threefold.io/-/commits/master)

##  Setup

```bash
$ docker build -t blt-centos .
```

## Development

```bash
$ docker run --mount type=bind,source="$(pwd)",target=/app blt-centos 
```

## Tests

```bash
$ docker run --mount type=bind,source="$(pwd)",target=/app blt-centos composer test
```

## Run

```bash
$ docker run blt-centos www.example.com
```


## Flags

```bash
$ docker run blt-centos www.example.com --enable-js
# For JavaScript generated pages, defaults to false

$ docker run blt-centos www.example.com --invalid-urls
# Print invalid URLs only

$ docker run blt-centos www.example.com --valid-urls
# Print valid URLs only

# By default prints all URLs 
```

### Uncrawlable HTTP Code

Code: 1000
Reason: Failed to crawl

Usually, it is seen for sites not accepting web crawler or require user authorization

