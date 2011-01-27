<?php
header("Content-type: text/html; charset=utf-8");
error_reporting(E_ERROR);
require_once 'libs/diff/diff.php';
require_once 'libs/diff/renderer.php';
require_once 'libs/diff/renderer/context.php';
require_once 'libs/diff/renderer/inline.php';
require_once 'libs/diff/renderer/unified.php';
$left='/home/jacmoe/HandleRepositoriesCommand@647.php';
$right='/home/jacmoe/HandleRepositoriesCommand@646.php';

$f1 = htmlspecialchars(file_get_contents($left));
$f2 = htmlspecialchars(file_get_contents($right));
$lines1 = explode("\n",$f1);
$lines2 = explode("\n",$f2);

$diff     = new Text_Diff('auto', array($lines1, $lines2));
$r = new Text_Diff_Renderer(
    array(
        'leading_context_lines' => 0,
        'trailing_context_lines' => 0
    )
);

$r_context = new Text_Diff_Renderer_context(
    array(
        'leading_context_lines' => 1,
        'trailing_context_lines' => 1,
        'chg_prefix' => '<span class="change">',
        'chg_suffix' => '</span>'
    )
);

$r_inline = new Text_Diff_Renderer_inline(
    array(
        'leading_context_lines' => 1,
        'trailing_context_lines' => 1,
        'ins_prefix' => '<span class="added">',
        'ins_suffix' => '</span>',
        'del_prefix' => '<span class="deleted">',
        'del_suffix' => '</span>'
    )
);

$r_unified = new Text_Diff_Renderer_unified(
    array(
        'leading_context_lines' => 1,
        'trailing_context_lines' => 1,
        'ins_prefix' => '<span class="added">',
        'ins_suffix' => '</span>',
        'del_prefix' => '<span class="deleted">',
        'del_suffix' => '</span>'
    )
);
?>
<html>
  <head>
    <title>diff</title>
    <style type="text/css">
      .deleted {background-color:#ffdddd;}
      .added {background-color:#ddffdd;}
      .context {background-color: #ffffff;}
      .change {background-color:#ffffdd;}
      .new-block {border-top: 10px solid #ffffff;}
      table {border-width: 0; border-collapse:collapse; font-family: Monospace;}
      td {vertical-align: top;}
    </style>
  </head>
  <body>
    <h1>Default-Diff</h1>
    <pre>
<?php echo $r->render($diff); ?>
    </pre>
    <br>
    <br>
    <h1>Context-Diff</h1>
    <pre>
<?php echo $r_context->render($diff); ?>
    </pre>
    <br>
    <br>
    <h1>Inline-Diff</h1>
    <pre>
<?php echo$r_inline->render($diff); ?>
    </pre>
    <br>
    <br>
    <h1>Unified-Diff</h1>
    <pre>
<?php echo $r_unified->render($diff);?>
    </pre>
  </body>
</html>