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
 * Copyright (C) 2009 - 2011 Bugitor Team
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
$this->pageTitle = $model->name . ' - Code - ' . Yii::app()->name;
?>
<div class="contextual">
            <?php
            if (((Yii::app()->controller->id === 'project') || (Yii::app()->controller->id === 'issue')) && (isset($_GET['identifier']))) {
                if (('issue/view' !== $this->route) && ('issue/update' !== $this->route) && ('issue/create' !== $this->route)) {
                    $this->widget('DropDownRedirect', array(
                        'data' => Yii::app()->controller->getProjects(),
                        'url' => $this->createUrl($this->route, array_merge($_GET, array('identifier' => '__value__'))),
                        'select' => $_GET['identifier'], //the preselected value
                    ));
                }
            }
            ?>
</div>
<h3 class="code">Code</h3>
<div id="changelog">
<div id="codemenu" class="box">
    <?php
    $this->widget('BugitorMenu', array(
        'items' => array(
            array('label' => 'Overview', 'url' => array('/projects/' . $_GET['identifier'] . '/code'), 'id' => 'project/code'),
            array('label' => 'Changesets', 'url' => array('/projects/' . $_GET['identifier'] . '/changesets'), 'id' => 'changeset/index'),
//            array('label' => 'Source', 'url' => array('/projects/' . $_GET['identifier'] . '/code'), 'id' => 'project/index'),
        ),
    )); ?>
</div>
<div id="shortlogs-changes">
    <?php foreach($model->repositories as $repository) : ?>
    <div id="changesets-inner"><h3><?php echo ucfirst($repository->name) ?></h3></div>
    <table class="maintable">
              <thead>
                <tr>
                  <th class="box">Author</th>
                  <th class="box">When</th>
                  <th class="box">Message</th>
                  <th class="box">Added</th>
                  <th class="box">Modified</th>
                  <th class="box">Deleted</th>
                </tr>
              </thead>
              <tbody>
            <?php foreach($repository->changesets as $changeset) : ?>
                <tr>
                  <td>
                    <?php echo Bugitor::gravatar($changeset->user, 16) . Bugitor::link_to_user_author($changeset->user, $changeset->author) ?>
                  </td>
                  <td>
                    <?php echo Bugitor::timeAgoInWords($changeset->commit_date) ?>
                  </td>
                  <td>
                        <?php echo CHtml::link(Bugitor::format_activity_description($changeset->message), $this->createUrl('changeset/view', array('id' => $changeset->id, 'identifier' => $_GET['identifier']))) ?>
                  </td>
                  <td>
                    <?php echo $changeset->add ?>
                  </td>
                  <td>
                    <?php echo $changeset->edit ?>
                  </td>
                  <td>
                    <?php echo $changeset->del ?>
                  </td>
                </tr>
        <?php endforeach ?>
              </tbody>
            </table>
    <?php endforeach ?>
</div>
</div>