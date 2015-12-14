#!/bin/sh
git remote add production ssh://travis@phpminds.org/home/travis/phpminds.git
git push production develop 

return 0
