[![Docker Image Latest Badge](https://ghcr-badge.egpl.dev/sfu-dhil/mvm/latest_tag?trim=major&label=latest)](https://github.com/sfu-dhil/mvm/pkgs/container/mvm)
[![Docker Image Size badge](https://ghcr-badge.egpl.dev/sfu-dhil/mvm/size)](https://github.com/sfu-dhil/mvm/pkgs/container/mvm)

# Manuscript Verse Miscellanies

[Manuscript Verse Miscellanies][https://dhil.lib.sfu.ca/mvm] (affectionately known as MVM) is a PHP application written using the
[Symfony Framework][https://symfony.com]. It is a digital tool for collecting metadata about
compiled manuscripts.

## Requirements

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- A copy of the `mvm.sql` database sql file. If you are not sure what these are or where to get them, you should contact the [Digital Humanities Innovation Lab](mailto:dhil@sfu.ca) for access. This file should be placed in the root folder.

## Initialize the Application

First you must setup the database for the first time

    docker compose up -d db
    # wait 30 after the command has fully completed
    docker exec -it mvm_db bash -c "mysql -u mvm -ppassword mvm < /mvm.sql"

Next you must start the whole application

    docker compose up -d --build

MVM will now be available at `http://localhost:8080/`

### Create your admin user credentials

    docker exec -it mvm_app ./bin/console nines:user:create <your@email.address> '<your full name>' '<affiliation>'
    docker exec -it mvm_app ./bin/console nines:user:password <your@email.address> <password>
    docker exec -it mvm_app ./bin/console nines:user:promote <your@email.address> ROLE_ADMIN
    docker exec -it mvm_app ./bin/console nines:user:activate <your@email.address>

example:

    docker exec -it mvm_app ./bin/console nines:user:create test@test.com 'Test User' 'DHIL'
    docker exec -it mvm_app ./bin/console nines:user:password test@test.com test_password
    docker exec -it mvm_app ./bin/console nines:user:promote test@test.com ROLE_ADMIN
    docker exec -it mvm_app ./bin/console nines:user:activate test@test.com

## General Usage

### Starting the Application

    docker compose up -d

### Stopping the Application

    docker compose down

### Rebuilding the Application (after upstream or js/php package changes)

    docker compose up -d --build

### Viewing logs (each container)

    docker logs -f mvm_app
    docker logs -f mvm_db
    docker logs -f mvm_mail

### Accessing the Application

    http://localhost:8080/

### Accessing the Database

Command line:

    docker exec -it mvm_db mysql -u mvm -ppassword mvm

Through a database management tool:
- Host:`127.0.0.1`
- Port: `13306`
- Username: `mvm`
- Password: `password`

### Accessing Mailhog (catches emails sent by the app)

    http://localhost:8025/

### Database Migrations

Migrate up to latest

    docker exec -it mvm_app make migrate

## Updating Application Dependencies

### Yarn (javascript)

First setup an image to build the yarn deps in

    docker build -t mvm_yarn_helper --target mvm-prod-assets .

Then run the following as needed

    # add new package
    docker run -it --rm -v $(pwd)/public:/app mvm_yarn_helper yarn add [package]

    # update a package
    docker run -it --rm -v $(pwd)/public:/app mvm_yarn_helper yarn upgrade [package]

    # update all packages
    docker run -it --rm -v $(pwd)/public:/app mvm_yarn_helper yarn upgrade

Note: If you are having problems starting/building the application due to javascript dependencies issues you can also run a standalone node container to help resolve them

    docker run -it --rm -v $(pwd)/public:/app -w /app node:19.5 bash

    [check Dockerfile for the 'apt-get update' code piece of mvm-prod-assets]

    yarn ...

After you update a dependency make sure to rebuild the images

    docker compose down
    docker compose up -d

### Composer (php)

    # add new package
    docker exec -it mvm_app composer require [vendor/package]

    # add new dev package
    docker exec -it mvm_app composer require --dev [vendor/package]

    # update a package
    docker exec -it mvm_app composer update [vendor/package]

    # update all packages
    docker exec -it mvm_app composer update

Note: If you are having problems starting/building the application due to php dependencies issues you can also run a standalone php container to help resolve them

    docker run -it -v $(pwd):/var/www/html -w /var/www/html ghcr.io/sfu-dhil/symfony-base:php-8.2-apache bash

    [check Dockerfile for the 'apt-get update' code piece of mvm]

    composer ...

After you update a dependency make sure to rebuild the images

    docker compose down
    docker compose up -d

## Tests

First make sure the application and database are started with `docker compose up -d`

### Unit Tests

    docker exec -it mvm_app make test

### Generate Code Coverage

    docker exec -it mvm_app make test.cover
    make test.cover.view

If the coverage file doesn't open automatically you can manually open it `coverage/index.html`

## Misc

### PHP Code standards

See standards errors

    docker exec -it mvm_app make lint-all
    docker exec -it mvm_app make symlint

    # or
    docker exec -it mvm_app make stan
    docker exec -it mvm_app make twiglint
    docker exec -it mvm_app make twigcs
    docker exec -it mvm_app make yamllint
    docker exec -it mvm_app make symlint


Automatically fix some standards errors

    docker exec -it mvm_app make fix.all

### Debug helpers

    docker exec -it mvm_app make dump.autowire
    docker exec -it mvm_app make dump.container
    docker exec -it mvm_app make dump.env
    docker exec -it mvm_app make dump.params
    docker exec -it mvm_app make dump.router
    docker exec -it mvm_app make dump.twig

# Build static archive

## Add Static search

Get static search
```bash
docker run --rm -it \
    --platform linux/arm64 \
    --volume ${PWD}/static:/var/www \
    dhilsfu/static-base:latest ant getStaticSearch
```

edit `<target name="validate.html">` in `static/lib/staticSearch/build.xml` to add the the xmlcatalog

```xml
  <target name="validate.html">
    <description>
      TARGET: validate.html
      Target to check input files as well-formed XHTML. Note that this DOES
      NOT check whether or not it is valid XHTML: only that it is well-formed. This target also
      checks the config file and sees if it's invalid. We don't fail if it's invalid, though, since
      we must assume that some people just don't care: but raise the error anyway.
    </description>
    <echo message="Validating ${ssCollectionDirName.converted} for well-formedness"/>
    <!--@lenient='true' since we are only checking well-formedness-->
    <xmlvalidate lenient="true">
      <!--TODO: This should really validate the actual set of files, and not just any-->
      <fileset dir="${ssCollectionDirName.converted}" casesensitive="false">
        <filename regex=".+\..?htm.?$"/>
        <!--Don't validate items that may be in a different staticSearch output-->
        <exclude name="**/${staticSearchTempFolder}/**"/>
        <exclude name="**/staticSearch_report.html"/>
      </fileset>
      <!-- THIS IS THE NEW STUFF -->
      <xmlcatalog>
        <dtd publicId="-//W3C//DTD XHTML 1.0 Transitional//EN" location="../../xhtml1-transitional.dtd"/>
      </xmlcatalog>
    </xmlvalidate>
  </target>
```

edit `<target name="tokenize">` in `static/lib/staticSearch/build.xml` to add the the xmlcatalog

```xml
  <target name="tokenize">
    <description>
      TARGET: tokenize
      This target runs the multi-stage tokenization process on the
      XHTML collection and creates a stash of tokenized and cleaned up files from which we generate
      the individual token JSONs. The XSLT is run on itself and uses the information in the config
      XSLT module (generated in the config task) to determine the input files, et cetera. </description>
    <echo message="Creating tokenized XHTML files..."/>
    <loadfile srcFile="${ssPatternsetFile}" property="ptn" failonerror="true"/>

    <xslt
      style="${ssBaseDir.converted}/xsl/tokenize.xsl" classpath="${ssSaxon}" destdir="${staticSearchTempDir}"
       reloadstylesheet="true"
       force="true"
      useimplicitfileset="false">
      <factory name="net.sf.saxon.TransformerFactoryImpl"/>
      <fileset id="siteFiles" dir="${ssCollectionDirName.converted}" casesensitive="no">
        <includesfile name="${ssPatternsetFile}"/>
        <!--Exclude the temporary directory's basename-->
        <exclude name="**/${staticSearchTempFolder}/**"/>
      </fileset>
      <!-- THIS IS THE NEW STUFF -->
      <xmlcatalog>
        <dtd publicId="-//W3C//DTD XHTML 1.0 Transitional//EN" location="../../xhtml1-transitional.dtd"/>
      </xmlcatalog>

       <regexpmapper from="^(.*)\.([^\.]+$)" to="\1_tokenized.html"/>
    </xslt>
    <!--Necessary to delete any empty files (i.e. files excluded by
        the configuration) -->
    <delete verbose="${ssVerbose}">
      <fileset dir="${staticSearchTempDir}">
        <size value="0"/>
      </fileset>
    </delete>
  </target>
```


## Crawl for archive

```bash
docker compose up -d --build
docker exec -it mvm_app mkdir -p /var/www/html/var/cache/prod
docker exec -it mvm_app chown www-data:www-data /var/www/html/var/cache/prod
```

```bash
wget --execute robots=off --user-agent=mozilla --recursive --level=0 --parent=off \
    --clobber=off --page-requisites --ignore-case --content-disposition --trust-server-names \
    --convert-links --adjust-extension --restrict-file-names=windows \
    --wait 0 --tries=0 --retry-connrefused --retry-on-http-error='*,!404' \
    --reject-regex='^data:image\/svg\+xml,.*$|^data:application\/font-woff;charset=utf-8;base64,.*$' \
    --no-host-directories -P ./static/_build \
    --no-check-certificate \
    https://localhost
```

## Add static search to archive

Run static search
```bash
docker run --rm -it \
    --platform linux/arm64 \
    --volume ${PWD}/static:/var/www \
    dhilsfu/static-base:latest ant
```

## Run static website

```bash
docker run --rm -p 8080:80 -v "$(pwd)/static/_site:/usr/share/nginx/html:ro" --name mvm_static nginx:1.31-alpine
```