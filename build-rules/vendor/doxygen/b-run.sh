#!/bin/sh

DOXYGEN_HAS=`which doxygen`
if [ -z "$DOXYGEN_HAS" ]; then
	echo "Doxygen not installed" >&2
	exit 1
fi
