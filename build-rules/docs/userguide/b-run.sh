#!/bin/sh

cp examples/api/index.php docs/userguide/api.php
cp examples/all_syntax/view/account/list.tpl docs/userguide/
cp examples/all_syntax/account_list.php docs/userguide/

cd docs/userguide/
asciidoc -a theme=classy -a toc -a toclevels=3 -a numbered userguide.txt
