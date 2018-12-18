<?php


class OHMSExportPlugin extends Omeka_Plugin_AbstractPlugin
{

  //Add filters
//  protected $_filters = array(
//    'admin_navigation_main'
//  );

  // Define Hooks
  protected $_hooks = array(
    'install',
    'uninstall',
    'admin_items_browse',
    'admin_items_show_sidebar',
    'admin_collections_show_sidebar'

  );

  public function hookInstall()
  {
    $table = get_db()->getTable('ElementSet');
    $dublinCore = $table->findByName('Dublin Core');
    $defaults = array(
      'elementSets' => array(),
    );
    if (isset($dublinCore->id)) {
      $defaults['elementSets'][$dublinCore->id] = TRUE;
    }
    set_option('ohms_export_settings', serialize($defaults));
  }

  public function hookUninstall()
  {
    delete_option('ohms_export_settings');
  }


//  public function filterAdminNavigationMain($nav)
//  {

//    $nav[] = array(
//      'label' => __('OHMS Export'),
//      'uri' => url('/ohms-export/index')
//    );
//    return $nav;
//  }

  // Adds a button to the admin search page for OHMS export
  public function hookAdminItemsBrowse($items)
  {
    if (isset($_GET['search']) && count($items)) {
      $params = array();
      foreach ($_GET as $key => $value) {
        $params[$key] = $value;
      }
      try {
        $params['hits'] = ZEND_REGISTRY::get('total_results');
      } catch (Zend_Exception $e) {
        $params['hits'] = 0;
      }
//      echo "<p><a class='button blue' href='" . url('ohms-export/export/ohms', $params) . "'><input style='background-color:transparent;color:white;border:none;' type='button' value='Export results as OHMS' /></a></p>";
    } else {
//      echo "<a class='button blue' href='" . url('ohms-export/export/ohms') . "'><input style='background-color:transparent;color:white;border:none;' type='button' value='Export all data as OHMS' /></a>";
    }
  }
  
  // Adds buttons to to admin show page sidebar for OHMS export
  public function hookAdminItemsShowSidebar()
  {
      $item = get_current_record('item');
      $itemId = $item->id;
      
    // get interview restriction field 
    $restricted = metadata($item, array("Dublin Core", "Rights"));
    $restricted = strip_formatting($restricted);
    $chkressubstr = "Restricted";
      
    // get suppression field 
    $suppressed = metadata($item, array("Suppression", "Suppressed -Suppress description"));
    $suppressed = strip_formatting($suppressed);
    $supsubstr = preg_replace('/\s+/', '', $suppressed);
    $chksupsubstr = "Description suppressed: Yes";
    $chksupsubstr = preg_replace('/\s+/', '', $chksupsubstr);
      
      
      $collection = get_collection_for_item();
      $collectionId = metadata($collection, 'id');
      
      if (strpos($supsubstr, $chksupsubstr) !== false) {
          echo "<div class=\"panel\"><h4>Export OHMS XML</h4><p><a class='button blue' style='width:100%;' href='" . url('ohms-export/export/ohms?search=&advanced%5B0%5D%5Bjoiner%5D=and&advanced%5B0%5D%5Belement_id%5D=232&advanced%5B0%5D%5Btype%5D=matches&advanced%5B0%5D%5Bterms%5D=False&advanced%5B0%5D%5Belement_id%5D=47&advanced%5B0%5D%5Btype%5D=is+exactly&advanced%5B0%5D%5Bterms%5D=Unrestricted&range=&collection=' . $collectionId . '&type=&user=&tags=&public=&featured=&submit_search=Search+for+items&hits=0') . "'><input style='background-color:transparent;color:white;border:none;' type='button' value='Export Collection as OHMS XML' /></a></p></div>"; 
      } elseif (strpos($restricted, $chkressubstr) !== false) {
                    echo "<div class=\"panel\"><h4>Export OHMS XML</h4><p><a class='button blue' style='width:100%;' href='" . url('ohms-export/export/ohms?search=&advanced%5B0%5D%5Bjoiner%5D=and&advanced%5B0%5D%5Belement_id%5D=232&advanced%5B0%5D%5Btype%5D=matches&advanced%5B0%5D%5Bterms%5D=False&advanced%5B0%5D%5Belement_id%5D=47&advanced%5B0%5D%5Btype%5D=is+exactly&advanced%5B0%5D%5Bterms%5D=Unrestricted&range=&collection=' . $collectionId . '&type=&user=&tags=&public=&featured=&submit_search=Search+for+items&hits=0') . "'><input style='background-color:transparent;color:white;border:none;' type='button' value='Export Collection as OHMS XML' /></a></p></div>";
          } else {
      echo "<div class=\"panel\"><h4>Export OHMS XML</h4><p><a class='button blue' style='width:100%;' href='" . url('ohms-export/export/ohms?search=&advanced%5B0%5D%5Bjoiner%5D=and&advanced%5B0%5D%5Belement_id%5D=&advanced%5B0%5D%5Btype%5D=&advanced%5B0%5D%5Bterms%5D=&range=' . $itemId . '&collection=&type=&user=&tags=&public=&featured=&submit_search=Search+for+items&hits=0') . "'><input style='background-color:transparent;color:white;border:none;' type='button' value='Export Item as OHMS XML' /></a></p><p><a class='button blue' style='width:100%;' href='" . url('ohms-export/export/ohms?search=&advanced%5B0%5D%5Bjoiner%5D=and&advanced%5B0%5D%5Belement_id%5D=232&advanced%5B0%5D%5Btype%5D=matches&advanced%5B0%5D%5Bterms%5D=False&advanced%5B0%5D%5Belement_id%5D=47&advanced%5B0%5D%5Btype%5D=is+exactly&advanced%5B0%5D%5Bterms%5D=Unrestricted&range=&collection=' . $collectionId . '&type=&user=&tags=&public=&featured=&submit_search=Search+for+items&hits=0') . "'><input style='background-color:transparent;color:white;border:none;' type='button' value='Export Collection as OHMS XML' /></a></p></div>";  }
  }
  
  public function hookAdminCollectionsShowSidebar()
  {
            $collectionRec = get_current_record('collection');
            $collectionID = $collectionRec->id;
echo "<div class=\"panel\"><h4>Export OHMS XML</h4><p><a class='button blue' style='width:100%;' href='" . url('ohms-export/export/ohms?search=&advanced%5B0%5D%5Bjoiner%5D=and&advanced%5B0%5D%5Belement_id%5D=232&advanced%5B0%5D%5Btype%5D=matches&advanced%5B0%5D%5Bterms%5D=False&advanced%5B0%5D%5Belement_id%5D=47&advanced%5B0%5D%5Btype%5D=is+exactly&advanced%5B0%5D%5Bterms%5D=Unrestricted&range=&collection=' . $collectionID . '&type=&user=&tags=&public=&featured=&submit_search=Search+for+items&hits=0') . "'><input style='background-color:transparent;color:white;border:none;' type='button' value='Export Collection as OHMS XML' /></a></p></div>";
  }
  

}

?>

