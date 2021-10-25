# Broken Links Tool for QA purposes

<<<<<<< HEAD
[![pipeline status](https://git.alleo.tech/alt-projects/tff/tffict/threefold.io/badges/master/pipeline.svg)](https://git.alleo.tech/alt-projects/alc/alcbdb/rpcplus.alleochain.com/-/commits/master)
[![coverage report](https://git.alleo.tech/alt-projects/tff/tffict/threefold.io/badges/master/coverage.svg)](https://git.alleo.tech/alt-projects/alc/alcbdb/rpcplus.alleochain.com/-/commits/master)
=======
[![pipeline status](https://git.alleo.tech/alt-projects/tff/tffict/threefold.io/badges/master/pipeline.svg)](https://git.alleo.tech/alt-projects/tff/tffict/threefold.io/-/commits/master)
[![coverage report](https://git.alleo.tech/alt-projects/tff/tffict/threefold.io/badges/master/coverage.svg)](https://git.alleo.tech/alt-projects/tff/tffict/threefold.io/-/commits/master)
>>>>>>> @{-1}

##  Setup

```bash
<<<<<<< HEAD
$ docker build -t blt-centos:latest .
=======
$ docker build -t blt-centos .
>>>>>>> @{-1}
```

## Development

```bash
<<<<<<< HEAD
# Run container and keep it alive to apply changes
$ docker run --rm -it --entrypoint bash --mount type=bind,source="$(pwd)",target=/app blt-centos:latest 
# Within container
$ bin/broken-links-tool https://example.com
=======
$ docker run --mount type=bind,source="$(pwd)",target=/app blt-centos 
>>>>>>> @{-1}
```

## Tests

```bash
<<<<<<< HEAD
$ docker run --rm -it --entrypoint bash --mount type=bind,source="$(pwd)",target=/app blt-centos:latest 
$ bin/composer test
```

## Create container and Run

```bash
$ docker run --rm blt-centos:latest https://example.com
=======
$ docker run --mount type=bind,source="$(pwd)",target=/app blt-centos composer test
```

## Run

```bash
$ docker run blt-centos www.example.com
>>>>>>> @{-1}
```


## Flags

```bash
<<<<<<< HEAD
$ docker run --rm blt-centos:latest https://example.com ---enable-js
# For JavaScript generated pages, defaults to false

$ docker run --rm blt-centos:latest https://example.com ---invalid-urls
# Print invalid URLs only

$ docker run --rm blt-centos:latest https://example.com ---valid-urls
=======
$ docker run blt-centos www.example.com --enable-js
# For JavaScript generated pages, defaults to false

$ docker run blt-centos www.example.com --invalid-urls
# Print invalid URLs only

$ docker run blt-centos www.example.com --valid-urls
>>>>>>> @{-1}
# Print valid URLs only

# By default prints all URLs 
```

### Uncrawlable HTTP Code

Code: 1000
Reason: Failed to crawl

Usually, it is seen for sites not accepting web crawler or require user authorization

