<?php
$title = metadata($item, array("General", "Interview Accession"));
$dir = realpath(dirname(__FILE__));
$subdir = "/tmp/";

    if ($zipFlag)
    {
	
        // set example variables
        $filename = "ohms_xml.zip";
        $filepath = "$dir$subdir";

        // http headers for zip downloads
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"".$filename."\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($filepath.$filename));
        ob_end_flush();
        @readfile($filepath.$filename);

    }
        else
    {

        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Content-Description: File Transfer');
        header("Content-type: text/xml");
        header("Content-Disposition: attachment; filename={$title}.xml");
        header("Expires: 0");
        header("Pragma: public");
 
        $file = fopen( 'php://output', 'w' );

        $header = false;

    foreach ( $result as $data )
    {

        $data = implode("",$data);
        fputs($file, $data);

    }

    fclose($file);


    
    }
    
    //clean the room
    $folder = "$dir$subdir";
 
    $files = glob($folder . '/*');
 
        foreach($files as $file){
            if(is_file($file)){
            unlink($file);
        }
    }
    
    exit;
?>
