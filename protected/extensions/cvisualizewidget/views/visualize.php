<table id="<?php echo $this->tableID; ?>" class="visualize">
  <thead>
    <tr>
      <td></td>
	  <?php foreach($data['headings'] as $heading) : ?>
      <th scope="col"><?php echo CHtml::encode($heading); ?></th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach(array_keys($data['data']) as $key) : ?>
    <tr>
      <th scope="row"><?php echo CHtml::encode($key); ?></th>
      <?php foreach($data['data'][$key] as $value) : ?>
      <td><?php echo (int)$value; ?></td>
      <?php endforeach; ?>
      <?php
      
	  /**
	   * Draw remaining blank cells
	   */
	  if(count($data['headings']) > count($data['data'][$key])) {
		  for($i=0;$i<(count($data['headings']) - count($data['data'][$key]));$i++) {
			?>
            <td>0</td>
            <?php  
		  }
	  }
	  ?>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function($)
{
	$('table#<?php echo $this->tableID; ?>').visualize({
		type:'<?php echo $this->type; ?>',
		<?php foreach(array_keys($options) as $opt) : ?>
		<?php echo $opt; ?>:<?php echo is_int($options[$opt]) ? (int)$options[$opt] : is_array($options[$opt]) ? CJSON::encode($options[$opt]) : '"'.$options[$opt].'"'; ?>,
		<?php endforeach; ?>
	});
});
//]]>
</script>