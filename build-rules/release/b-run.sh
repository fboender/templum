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
mkdir release/templum-$VERSION/templum
cp docs/userguide/userguide.html release/templum-$VERSION/docs/
cp src/templum.php release/templum-$VERSION/templum/
cd release
tar -czf templum-src-$VERSION.tar.gz templum-$VERSION
cd ..
rm -rf release/templum-$VERSION 
