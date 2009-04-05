#!/bin/sh

VERSION=`cat pear/package.xml | egrep "<release>.*</release>" | head -n1 | cut -d">" -f2- | cut -d"<" -f1`

mkdir release

cp pear/package.xml src/
cd src
pear package > /dev/null
cd ..

mv src/*.tgz ./release
rm src/package.xml

mkdir release/templum-$VERSION
mkdir release/templum-$VERSION/docs
mkdir release/templum-$VERSION/docs/userguide
mkdir release/templum-$VERSION/docs/api
mkdir release/templum-$VERSION/src
cp docs/userguide/userguide.html release/templum-$VERSION/docs/userguide/
cp docs/license/LICENSE.txt release/templum-$VERSION/
cp docs/readme/README.txt release/templum-$VERSION/
cp -ar examples release/templum-$VERSION/
cp -r docs/api/html release/templum-$VERSION/docs/api/
cp src/templum.php release/templum-$VERSION/src/
cd release
find templum-$VERSION -name '.svn' -exec rm -rf '{}' \; 2>/dev/null
tar -czf templum-src-$VERSION.tar.gz templum-$VERSION
cd ..
rm -rf release/templum-$VERSION 
