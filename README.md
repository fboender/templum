Templum
=======


Introduction
------------

Templum is an extremely *lightweight*, *simple* yet *powerful* and *fast*
templating engine for PHP. It re-uses the power of PHP itself for rendering
templates, but provides additional features making it easier to write
templating code.  Rendering templates using Templum is very fast; it
approximates native PHP rendering speed for include() statements.


Features
--------

*   Lightweight. About 140 lines of code (excluding ±140 lines of API comments).
*   Re-uses PHP alternative syntax for clarity and full power. (You can also use normal syntax)
*   Very fast. Renders 10,000 templates in 0.741s (Native PHP takes 0.633s).
*   I18N (translated) templates.
*   Per-session caching of rendered templates.
*   Inheritance.
*   Including other templates.
*   Security by automatic encoding of HTML entities.
*   Universal, global and local variables.
*   PHP v4 and v5 support.

The template language features the following syntax:

    {{ and }}

Echo's the variable, function or other PHP printables between the accolades. Echo'ed contents is automatically escaped using htmlspecialchars() (can be turned off).

    [[ and ]]

Start a PHP code block.

    @line

Interpret a line starting with an at-sign as a line of PHP code.


Examples
--------

The following is an example of an simple controller file. It defines some data (which really should be coming from a model and a database), creates a new Templum instance and renders a template.

**/index.php:**

    <?php

    require_once('templum.php');

    // Define some data. This might as well have come from a database.
    $username = 'jjohnson';
    $accounts = array(
       array('id'=>1, 'username'=>'jjohnson',  'realname'=>'John Johnson'),
       array('id'=>2, 'username'=>'ppeterson', 'realname'=>'Pete Peterson'),
       array('id'=>3, 'username'=>'jdoe',      'realname'=>'John Doe'),
    );

    // Create the Template engine with the base path for the templates.
    $templum = new Templum('view');

    // Set a universal variable which will be available in every template created
    // using the template engine.
    $templum->setVar('username', $username);

    // Retrieve and render a template with the data in $accounts as a local
    // variable and $username as a universal variable.
    $tpl = $templum->template('account/list');
    print($tpl->render(compact('accounts')));

    ?>

The template file called in the above example looks like this:

**/view/account/list.tpl:**

    [[
    if (!function_exists('helperBtnAction')) {
       function helperBtnAction($action, $id, $icon) {
          echo('<a href="?action='.$action.'&id='.$id.'">');
          echo('<img src="ico/'.$icon.'.png" alt="'.$icon.'" border="0" />');
          echo('</a>');
       };
    };
    ]]
    <h1>User list</h1>

    <p>Hello {{$username}}, here's a list of all the users:</p>

    <div id="accounts">
       @if (count($accounts) <= 0):
          No accounts found.
       @else:
          <table>
             <tr>
                <th>&nbsp;</th>
                <th>Username</th>
                <th>Full naam</th>
             </tr>
             @foreach ($accounts as $account):
                <tr>
                   <td>[[helperBtnAction('account.edit', $account['id'], 'edit')]]</td>
                   <td>{{$account['username']}}</td>
                   <td>{{$account['realname']}}</td>
                </tr>
             @endforeach
             <tr>
                <td>[[helperBtnAction('account.add', '', 'add')]]</td>
                <td colspan="4">&nbsp;</td>
             </tr>
          </table>
       @endif
    </div>

More examples are available in the source package or in the git repository.


Documentation
-------------

*    [Userguide](http://www.electricmonk.nl/data/templum/latest/docs/userguide/userguide.html)
*    [API Reference](http://www.electricmonk.nl/data/templum/latest/docs/api/html/index.html)


Installation
------------

The preferred method is installation through PEAR:

    $ pear install https://bitbucket.org/fboender/templum/downloads/templum-0.4.0.tgz

Or you can install it manually by downloading an archive and running:

    $ pear install ./templum-0.4.0.tgz


Copyright
---------

Templum is copyright 2013 by Ferry Boender, released under the MIT license

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
of the Software, and to permit persons to whom the Software is furnished to do
so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

