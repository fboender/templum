#!/bin/sh

cp examples/api/index.php docs/userguide/api-index_php
cp examples/all_syntax/view/account/list.tpl docs/userguide/all_syntax-list_tpl
cp examples/all_syntax/index.php docs/userguide/all_syntax-index_php

cd docs/userguide/
asciidoc -a theme=classy -a toc -a toclevels=3 -a numbered userguide.txt
