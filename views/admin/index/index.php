<?php
    $title = __('OHMS Export');
    queue_css_file('ohmsexport');
    echo head(array('title' => html_escape($title), 'bodyclass' => 'csvexport'));

?>
<div id="primary">
	<?php echo flash(); ?>
	<input id="ohmsexportbutton" class="blue button" type='button' value='Export all data as CSV' onClick='window.location="<?php echo url(array('module'=>'csv-export', 'controller'=>'export', 'action'=>'csv'), 'default') ?>";'/>
	<p>The OHMS Export Plugin allow administrators to export individual interview records as well as groups of interview records.  Exporting groups of interview records is based on their project affiliation or search criteria.</p>
</div>
<?php echo foot(); ?>
