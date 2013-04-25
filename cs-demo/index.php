
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload Files to Amazon S3 PHP</title>
</head>

<body>
<form action="" method='post' enctype="multipart/form-data">
<h3>Upload PDF Files here</h3><br/>
<div style='margin:10px'>
<input type='file' name='file'/> <br> 
<br><br>
<input type='submit' value='Upload PDF Files'/></div>
</form>

<?php
include('pdf_check.php');
$msg='';
if($_SERVER['REQUEST_METHOD'] == "POST")
{

$name = $_FILES['file']['name'];
$size = $_FILES['file']['size'];
$tmp = $_FILES['file']['tmp_name'];
$ext = getExtension($name);

if(strlen($name) > 0)
{

if(in_array($ext,$valid_formats))
{
 
if($size<(2*1024*1024))
{
include('s3_config.php');
//Rename image name. 
$name = str_replace(" ","_",$name);
try 
{
$filepath='pdf/'.$name;
$s3->putObjectFile($tmp, $bucket , $filepath, S3::ACL_PUBLIC_READ); 

$msg = "Upload to S3 Successful. SDF Filed were created and posted to CloudSearch. Your Documents are ready to search now";	
$s3file='http://'.$bucket.'.s3.amazonaws.com/pdf/'.$name;
$sdfname=str_replace(".pdf","",$name);
$sdfurl='http://'.$bucket.'.s3.amazonaws.com/sdf/'.$sdfname.'1.sdf';
$createsdf=array();
exec('/opt/aws/scripts/bb-search.sh '.$name.' '.$sdfname.' 2>&1',$createsdf);
echo "<br/>";
echo '<b>S3 File URL:</b><a href='.$s3file.'>'.$s3file.'</a>';
echo "<br/>";
echo '<b>SDF File URL:</b><a href='.$sdfurl.'>'.$sdfurl.'</a>';
}
catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


}
else
$msg = "File size Max 2 MB";

}
else
$msg = "Invalid file, please upload a pdf file.";

}
else
$msg = "Please select a pdf file.";

} 
echo '<br/><br/>'.$msg.'<br/>'; 
?>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<input id="searchterm" type="text" /> <button id="search">search</button> <div id="results"></div>

<script type="text/javascript">// <![CDATA[
      $("#searchterm").keyup(function(e){
        var q = $("#searchterm").val();
       //$.getJSON("http://en.wikipedia.org/w/api.php?callback=?",
      $.getJSON("http://www.begurbites.com/request.php",
         //$.getJSON("http://search-imdb-test-sv2v6frwqh3uaszf22gnlbsrfq.us-east-1.cloudsearch.amazonaws.com/2011-02-01/search?",
        {
          q: q,
          'return-fields': "actor,title,text_relevance",
        },
        function(data) {
          $("#results").empty();
          $("#results").append("Results for <b>" + q + "</b>");
          $.each(data.hits.hit, function(i,item){
            $("#results").append("<div>" + item.id + item.data.title + "</div>");
          });
        });
      });

// ]]></script>



</body>
</html>

