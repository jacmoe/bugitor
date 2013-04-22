<?php
/*
 * This file is part of
 *     ____              _ __
 *    / __ )__  ______ _(_) /_____  _____
 *   / __  / / / / __ `/ / __/ __ \/ ___/
 *  / /_/ / /_/ / /_/ / / /_/ /_/ / /
 * /_____/\__,_/\__, /_/\__/\____/_/
 *             /____/
 * A Yii powered issue tracker
 * http://bitbucket.org/jacmoe/bugitor/
 *
 * Copyright (C) 2009 - 2013 Bugitor Team
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
?>
<?php
//$this->pageTitle = $model->name . ' - Code - ' . Yii::app()->name;
?>
<h3 class="code">Code</h3>
<div class="repo-desc-hidden" id="changelog">
<div id="codemenu" class="simplebox">
    <?php
    $this->widget('BugitorMenu', array(
        'items' => array(
            array('label' => 'Overview', 'url' => array('/projects/' . $_GET['identifier'] . '/code'), 'id' => 'notused'),
            array('label' => 'Changesets', 'url' => array('/projects/' . $_GET['identifier'] . '/changesets'), 'id' => 'changeset/index'),
//            array('label' => 'Source', 'url' => array('/projects/' . $_GET['identifier'] . '/code'), 'id' => 'project/index'),
        ),
    )); ?>
</div>

<?php //$this->widget('zii.widgets.CListView', array(
//	'dataProvider'=>$dataProvider,
//	'itemView'=>'_view',
//)); ?>
            <div class="removed"></div><div class="modified"></div><div class="added"></div>
            <div style="height: 1579px;" id="changesets-graph"><canvas id="graph" width="160" height="1579"></canvas></div>
            <div id="changesets-inner">
                <h3 class="simplebox">Changelog</h3>
                <ol>
                    <li id="chg_0">
                        <dl class="shortlog_relations">
                            <dt>commit</dt>
                            <dd><a href="#">606dd2a169a8</a></dd>
                            <dt>parents</dt>
                            <dd><a href="#">parents</a></dd>
                        </dl>
                        <h4>Files affected</h4>
                        <dl class="files-affected">
                            <dt>Added</dt>
                            <dd title="Added">-</dd>
                            <dt>Modified</dt>
                            <dd title="Modified">-</dd>
                            <dt>Removed</dt>
                            <dd title="Removed">-</dd>
                        </dl>
                        <h4>Tags</h4>
                        <ul class="tags">
                            <li class="default">branches</li>
                            <li class="tip">tag</li>
                        </ul>
                        <a href="#"><p>message</p></a>
                        <dl class="metadata">
                            <dt>Who</dt>
                            <dd>username</dd>
                            <dd>gravatar</dd>
                            <dt>When</dt>
                            <dd>time ago</dd>
                        </dl>
                    </li>
                    <li id="chg_1">
                        <dl class="shortlog_relations">
                            <dt>commit</dt>
                            <dd><a href="#">fd0ea86d2bf4</a></dd>
                            <dt>parents</dt>
                            <dd><a href="#">parents</a></dd>
                        </dl>
                        <h4>Files affected</h4>
                        <dl class="files-affected">
                            <dt>Added</dt>
                            <dd title="Added">-</dd>
                            <dt>Modified</dt>
                            <dd title="Modified">-</dd>
                            <dt>Removed</dt>
                            <dd title="Removed">-</dd>
                        </dl>
                        <h4>Tags</h4>
                        <ul class="tags">
                            <li class="default">branches</li>
                            <li class="tip">tag</li>
                        </ul>
                        <a href="#"><p>message</p></a>
                        <dl class="metadata">
                            <dt>Who</dt>
                            <dd>username</dd>
                            <dd>gravatar</dd>
                            <dt>When</dt>
                            <dd>time ago</dd>
                        </dl>
                    </li>
                    <li id="chg_2">
                        <dl class="shortlog_relations">
                            <dt>commit</dt>
                            <dd><a href="#">e4483bbdc31e</a></dd>
                            <dt>parents</dt>
                            <dd><a href="#">parents</a></dd>
                        </dl>
                        <h4>Files affected</h4>
                        <dl class="files-affected">
                            <dt>Added</dt>
                            <dd title="Added">-</dd>
                            <dt>Modified</dt>
                            <dd title="Modified">-</dd>
                            <dt>Removed</dt>
                            <dd title="Removed">-</dd>
                        </dl>
                        <h4>Tags</h4>
                        <ul class="tags">
                            <li class="default">branches</li>
                            <li class="tip">tag</li>
                        </ul>
                        <a href="#"><p>message</p></a>
                        <dl class="metadata">
                            <dt>Who</dt>
                            <dd>username</dd>
                            <dd>gravatar</dd>
                            <dt>When</dt>
                            <dd>time ago</dd>
                        </dl>
                    </li>
                    <li id="chg_3">
                        <dl class="shortlog_relations">
                            <dt>commit</dt>
                            <dd><a href="#">356df4e16854</a></dd>
                            <dt>parents</dt>
                            <dd><a href="#">parents</a></dd>
                        </dl>
                        <h4>Files affected</h4>
                        <dl class="files-affected">
                            <dt>Added</dt>
                            <dd title="Added">-</dd>
                            <dt>Modified</dt>
                            <dd title="Modified">-</dd>
                            <dt>Removed</dt>
                            <dd title="Removed">-</dd>
                        </dl>
                        <h4>Tags</h4>
                        <ul class="tags">
                            <li class="default">branches</li>
                            <li class="tip">tag</li>
                        </ul>
                        <a href="#"><p>message</p></a>
                        <dl class="metadata">
                            <dt>Who</dt>
                            <dd>username</dd>
                            <dd>gravatar</dd>
                            <dt>When</dt>
                            <dd>time ago</dd>
                        </dl>
                    </li>
                </ol>
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    var graph = $('<div id="changesets-graph"></div>'),
                    inner = $('#changesets-inner'),
                    h = inner.height();
                    graph.height(h).insertBefore(inner);
                    $('<canvas/>').attr('height', h).attr('width', graph.width()).attr('id', 'graph').appendTo(graph);
                });
            </script>
            <?php /*
             * node id, color
             * start end color
             */ ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    (new BranchRenderer()).render(
                    [
                        ["606dd2a169a8", [0, 1], [[0, 0, 1], [0, 1, 2]]],
                        ["fd0ea86d2bf4", [1, 2], [[0, 0, 1], [1, 1, 2]]],
                        ["e4483bbdc31e", [0, 1], [[0, 0, 1], [1, 1, 2]]],
                        ["356df4e16854", [0, 1], [[0, 0, 1], [1, 0, 2]]]
                        ]);
                });
            </script>
        </div>
