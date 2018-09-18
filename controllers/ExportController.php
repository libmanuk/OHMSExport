<?php

class OHMSExport_ExportController extends Omeka_Controller_AbstractActionController
{

  public function ohmsAction()
  {
    $search = false;
    
    if (isset($_GET['search'])) {
      $items = $this->ohms_search($_GET);
      $search = true;
    } else {
      $items = get_records('Item', array(), 0);
    }

    // Get all element sets except for legacy files data.
    $table = get_db()->getTable('ElementSet');
    $elementSetsAll = $table->fetchObjects($table->getSelect());
    $elementSets = array();

    // get all fields from a specific element set (eg. dublin core)
    $elements = array();
    foreach ($elementSets as $elementSet) {
      $elements = array_merge(
        $elements,
        get_db()->getTable('Element')->findBySet($elementSet->name)
      );
    }
    
    $total = count($items);
    $current_date = date("Y-m-d");
    $tag00 = "<?xml version=\"1.0\" encoding=\"utf-8\"?><ROOT><record id=\"";
    $tag01 = "\" dt=\"$current_date\"><version>4</version><date format=\"yyyy-mm-dd\">";
    $tag02 = "</date><date_nonpreferred_format/><cms_record_id>";
    $tag03 = "</cms_record_id><title>";
    $tag04 = "</title><accession>";
    $tag05 = "</accession><duration/><collection_id></collection_id><collection_name></collection_name><series_id>";
    $tag06 = "</series_id><series_name>";
    $tag07 = "</series_name><repository>Louie B. Nunn Center for Oral History, University of Kentucky</repository><repository_url/><interviewee>";
    $tag08 = "</interviewee><interviewer>";
    $tag09 = "</interviewer><file_name/><sync/><type>interview</type><description>";
    $tag10 = "</description><rel/><transcript/><rights>";
    $tag11 = "</rights><usage>";
    $tag12 = "</usage></record></ROOT>";
    
    if ($total === 1) {

        set_loop_records('items', $items);
            foreach (loop('items') as $item) {

            // get omeka id and add it to the ohms output
            $id = metadata($item, 'ID');
      
            // get interview restriction field 
            $restricted = metadata($item, array("Dublin Core", "Rights"));
      
            // get suppression field 
            $suppressed = metadata($item, array("Rights", "Suppressed -Suppress description"));
      
            // add in check for suppressed or restricted records
      
      

            $result[$id]['ohms'] = $tag00 . metadata($item, array("General", "Interview Accession")) . $tag01 . metadata($item, array("General", "Interview Date")) . $tag02 . metadata($item, array("General", "Interview Accession")) . $tag03 . metadata($item, array("Dublin Core", "Title")) . $tag04 . metadata($item, array("General", "Interview Accession")) . $tag05 . metadata($item, array("General", "Interview Series Identifier")) . $tag06 . metadata($item, "collection name") . $tag07 . metadata($item, array("General", "Interviewee Name")) . $tag08 . metadata($item, array("General", "Interviewer Name")) . $tag09 . metadata($item, array("General", "Interview Summary")) . $tag10 . metadata($item, array("Rights", "Interview Rights")) . $tag11 . metadata($item, array("Rights", "Interview Usage")) . $restricted . $suppressed . $tag12;

                $result[$id]['ohms'] = preg_replace("/[\n\r]/","",$result[$id]['ohms']);
                $result[$id]['ohms'] = str_replace("  "," ",$result[$id]['ohms']);

                }
    
          $this->view->assign('result', $result);
        }
    
    else  {

    $zipFlag = TRUE;
        
        set_loop_records('items', $items);
            foreach (loop('items') as $item) {
        
            $title = metadata($item, array("General", "Interview Accession"));
            $dir = realpath(__DIR__ . '/..');
            $subdir = "/views/admin/export/tmp/";
            $directory = "$dir$subdir";
            
            // get omeka id and add it to the ohms output
            $id = metadata($item, 'ID');
            
            // get interview restriction field 
            $restricted = metadata($item, array("Dublin Core", "Rights"));
      
            // get suppression field 
            $suppressed = metadata($item, array("Rights", "Suppressed -Suppress description"));
        
            $result[$id]['ohms'] = $tag00 . metadata($item, array("General", "Interview Accession")) . $tag01 . metadata($item, array("General", "Interview Date")) . $tag02 . metadata($item, array("General", "Interview Accession")) . $tag03 . metadata($item, array("Dublin Core", "Title")) . $tag04 . metadata($item, array("General", "Interview Accession")) . $tag05 . metadata($item, array("General", "Interview Series Identifier")) . $tag06 . metadata($item, "collection name") . $tag07 . metadata($item, array("General", "Interviewee Name")) . $tag08 . metadata($item, array("General", "Interviewer Name")) . $tag09 . metadata($item, array("General", "Interview Summary")) . $tag10 . metadata($item, array("Rights", "Interview Rights")) . $tag11 . metadata($item, array("Rights", "Interview Usage")) . $restricted . $suppressed . $tag12;

            $result[$id]['ohms'] = preg_replace("/[\n\r]/","",$result[$id]['ohms']);
            $result[$id]['ohms'] = str_replace("</ROOT>","</ROOT>\n",$result[$id]['ohms']);
            $result[$id]['ohms'] = str_replace("  "," ",$result[$id]['ohms']);
          
            $file = fopen("$dir$subdir$title.xml", "w");
            fputs($file, $result[$id]['ohms']);
            fclose($file);
    }
    
    $dir = realpath(__DIR__ . '/..');
    $subdir = "/views/admin/export/tmp/";
    $directory = "$dir$subdir";
    $zip_file = "ohms_xml.zip";
    $zipFile = "$directory$zip_file";
    $subdir2 = "/views/admin/export/tmp";
    $zipDir = "$dir$subdir2";
    $zip = new ZipArchive;
    $zip->open($zipFile, ZipArchive::CREATE);
        foreach (glob("$zipDir/*") as $file) {
            $zip->addFile($file,basename($file));
        }
    $zip->close();

    $result = $zipFile;
    $this->view->assign('result', $result);
    $this->view->assign('zipFlag', $zipFlag);
   
    }
  
  
  }
  

  function ohms_search($terms)
  {
    $itemTable = $this->_helper->db->getTable('Item');
    if (isset($_GET['search'])) {
      $items = $itemTable->findBy($_GET);
      return $items;
    } else {
      $queryArray = unserialize($itemTable->query);
      // Some parts of the advanced search check $_GET, others check
      // $_REQUEST, so we set both to be able to edit a previous query.
      $_GET = $queryArray;
      $_REQUEST = $queryArray;
      $items = $itemTable->findBy($_REQUEST);
      return $items;
    }
  }
  
    
}
