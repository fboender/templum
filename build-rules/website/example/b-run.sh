#!/bin/sh

tools/highlighter.php examples/all_syntax/account_list.php > website/examples/all_syntax/list.phps 
tools/highlighter.php examples/all_syntax/view/account/list.tpl > website/examples/all_syntax/list.tpl  
cd examples/all_syntax/
php account_list.php > ../../website/examples/all_syntax/output.html

