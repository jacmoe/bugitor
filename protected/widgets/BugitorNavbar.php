<?php

Yii::import('bootstrap.widgets.TbNavbar');

class BugitorNavbar extends TbNavbar
{
    /**
     *### .run()
     *
     * Runs the widget.
     */
    public function run()
    {
        echo CHtml::openTag('div', $this->htmlOptions);
        echo '<div class="navbar-inner"><div class="'.$this->getContainerCssClass().'">';

        $collapseId = TbCollapse::getNextContainerId();

        if ($this->collapse !== false)
        {
            echo '<a class="btn btn-navbar" data-toggle="collapse" data-target="#'.$collapseId.'">';
            echo '<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>';
            echo '</a>';
        }

        echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl . '/images/bugitor_32.png', 
            'Bugitor - The Yii-powered issue tracker', 
            array('title' => 'Bugitor - The Yii-powered issue tracker',
                'class' => 'brand', 'style' => 'position:absolute;float:left;top:-4px;left:4px;z-index:999;')), Yii::app()->controller->createUrl('project/index'));

        if ($this->brand !== false)
        {
            $this->brandOptions['class'] .= ' brandext';
            if ($this->brandUrl !== false)
                echo CHtml::openTag('a', $this->brandOptions).$this->brand.'</a>';
            else
            {
                unset($this->brandOptions['href']); // spans cannot have a href attribute
                echo CHtml::openTag('span', $this->brandOptions).$this->brand.'</span>';
            }
        }

        if ($this->collapse !== false)
        {
            $this->controller->beginWidget('bootstrap.widgets.TbCollapse', array(
                'id'=>$collapseId,
                'toggle'=>false, // navbars should be collapsed by default
                'htmlOptions'=>array('class'=>'nav-collapse'),
            ));
        }

        foreach ($this->items as $item)
        {
            if (is_string($item))
                echo $item;
            else
            {
                if (isset($item['class']))
                {
                    $className = $item['class'];
                    unset($item['class']);

                    $this->controller->widget($className, $item);
                }
            }
        }

        if ($this->collapse !== false)
            $this->controller->endWidget();

        echo '</div></div></div>';
    }
}