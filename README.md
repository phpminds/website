[![Build Status](https://travis-ci.org/phpminds/website.svg)](https://travis-ci.org/phpminds/website)

![](https://cdn.rawgit.com/phpminds/website/develop/public/imgs/phpminds.svg)

This is a new PHP User Group which is in Nottingham.

Our aim is to bring the PHP community together to collaborate, network and share knowledge in a friendly and professional environment.

We welcome people at all levels and from all backgrounds. If you’re interested in PHP, working with Drupal or WordPress or any web technologies, you’re most welcome to join us.

This repository contains the source code for 

# PHPMiNDS website


[Based on the Slim 3 Skeleton] (https://github.com/akrabat/slim3-skeleton) by @akrabat

Integrations with 

- [x] meetup.com
- [x] joind.in


## Tests

Running Behat:

`bin/behat -c tests/behat/behat.yml `

Running Selenium:

`java  -Dwebdriver.chrome.driver=./tests/drivers/linux/chromedriver -jar ./vendor/se/selenium-server-standalone/bin/selenium-server-standalone.jar -port 4444`

*Server Dependencies*

Java 8

Installation

```
add-apt-repository ppa:webupd8team/java
apt update
apt install oracle-java8-installer
```


## Contributors
Created by the [@phpminds](https://twitter.com/phpminds) team - contributions welcomed

[@pavlakis](https://twitter.com/pavlakis)
[@sdh100shaun](https://twitter.com/sdh100shaun)

Licence :  MIT see licence.md 
