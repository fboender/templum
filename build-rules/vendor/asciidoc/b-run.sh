#!/bin/sh

REQ_VERSION="^8.[23]"

ASCIIDOC_HAS=`which asciidoc`
if [ -z "$ASCIIDOC_HAS" ]; then
	echo "Asciidoc not installed" >&2
	exit 1
fi

ASCIIDOC_VERSION=`$ASCIIDOC_HAS --version | sed "s/asciidoc //"`
if [ -z "`echo $ASCIIDOC_VERSION | grep \"$REQ_VERSION\"`" ]; then
	echo "Incorrect asciidoc version. " >&2
	exit 2
fi
