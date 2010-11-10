<?php
/**
* @desc ajax utils for lms ...
* 
*/

class AjaxUtils
{

/**
 *  Prints a script to support ajax pagination, sorting and deleting
 *  Can be used on admin pages
 *  Pagination should have '.yiiPager' class for links (standard pagination has)
 *  Table header row with sort links should have '#sort-buttons' id
 *     ( <tr id="sort-buttons"> ...)
 *  Delete link should have "deleteItem" class
 *    ( echo CHtml::link('Delete', array('delete', 'id' => $model->id), array('class' => 'deleteItem')); )
 *  Table with data should be placed inside div with id="updateData"
 *       <div id="updateData">
 *       <?php
 *           $this->renderPartial('adminList', compact('models', 'pages', 'sort') );
 *       ?>
 *       </div>
 * 
 */
    public static function printAjaxPaginationAndDeleteScript($objectName = 'record', $info='') {

     /*This may prevent it from being seen if the user has scrolled down */
//     top:0;left:0;
//	color:#fff;
//	padding:2px 2px 2px 20px;
//	background:red url(../images/loading-indicator.gif) no-repeat 2px;
//	display:none;


        $script = <<<EOD

function showIndicator() {
        $('.dataGrid').after(
            '<div style="display:block;position:absolute;left:40%;top:60%;"><img src="/images/indicator.gif" style="vertical-align:middle;" />Loading...</div>'
        );
}

        
function updatePage() {
        showIndicator();
        $.ajax({'type':'GET','url':$(this).attr('href'), 'success':function(page) {
                $('#updateData').html(page);

                $('.yiiPager a').bind("click", updatePage);
                $('#updateData #sort-buttons a').bind("click", updatePage);
                $('#updateData .deleteItem').bind("click", deleteItem);
        
                if (typeof(window.editItem) != 'undefined') {
                    alert(window.editItem.attr('name'));
                    window.editItem.parent().parent().fadeOut('slow');
                    window.editItem.parent().parent().fadeIn('slow');
                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {                
                alert('Can not update data, status: ' + textStatus + '<br\>'+XMLHttpRequest.responseText);
            }
	});

	return false;
}

function deleteItem(){
	if (confirm('Are you sure you want to delete the {$objectName} "'+$(this).parent().parent().find(".recordname").html()+'"?')) {
        delItem = $(this);
		$.ajax({
            type: "POST",
            url: $(this).attr('href'),
            success: function(response) {                    
                    delItem.parent().parent().fadeOut("slow");
                },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('Can not delete record, status: ' + textStatus + '<br\>'+XMLHttpRequest.responseText);
            }

		});	
	}

	return false;
}

function fadeOutParentRow(item)
{    
    do {        
        if (item.attr('nodeName').toLowerCase() === 'tr') {
            item.fadeOut("slow");
            break;
        }
        if (item.attr('nodeName').toLowerCase() === 'html') {
            break;
        }
        if (item) item = item.parent();
    } while(item);
}

EOD;

$scriptInit = <<<EOD

$(".deleteItem").click(deleteItem);
$('#sort-buttons a, .yiiPager a').click(updatePage);

EOD;
        echo CHtml::script($script);
        //echo CHtml::script($scriptInit);
        Yii::app()->clientScript->registerScript('ajaxPagination', $scriptInit, CClientScript::POS_READY);
}


}
