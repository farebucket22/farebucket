<select id="source" name="source_value">
	<option value="">select a source</option>
	<?php for($i = 0 ; $i < count($stateid); $i++){?>
	<option value="<?php echo $stateid[$i];?>"><?php echo $statename[$i];?></option>
	<?php }?>
</select>