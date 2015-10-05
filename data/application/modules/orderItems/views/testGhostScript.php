<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
         $pdf='test.pdf';
         $quality=90;
         $res='300x300';
         $exportName="pdf_export_".time();

         echo $exportName."<br/>";
         echo $_SERVER['DOCUMENT_ROOT']."<br/>";
         $path = $_SERVER['DOCUMENT_ROOT']."/testGhost";
         echo $path."<br/>";
         echo $path.'/'.$exportName."<br/>";

         $exportPath=$path."/".$exportName."/fullres/%03d.jpg";
         //echo "<br/>Hello world: ".realpath(APPPATH . '../../');
         if(!mkdir($path.'/'.$exportName,0777,TRUE))
         {
             die('Failed to create Order and other folders...');
         }
         else
         {
            chmod($path, 0777);

             //change the directory owner/group permission for OrderItemID folder
            chmod($path.'/'.$exportName, 0777);
         }
         if(!mkdir($path.'/'.$exportName.'/fullres',0777,TRUE))
         {
             die('Failed to create Order and other folders...');
         }
         else
         {
            chmod($path.'/'.$exportName, 0777);

             //change the directory owner/group permission for OrderItemID folder
            chmod($path.'/'.$exportName.'/fullres', 0777);
         }

        set_time_limit(900);
        exec("'gs' '-dNOPAUSE' '-sDEVICE=jpeg' '-dUseCIEColor' '-dTextAlphaBits=4' '-dGraphicsAlphaBits=4' '-o$exportPath' '-g$res' '-dPDFFitPage' '-dJPEGQ=$quality' '$pdf'",$output);

        for($i=0;$i<count($output);$i++)
        {
            echo($output[$i] .'<br/>');

        }
        ?>
    </body>
</html>
