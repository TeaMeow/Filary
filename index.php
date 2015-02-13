<?php 
date_default_timezone_set('Asia/Taipei');

/** The array which we'll store the diays later */
$Diarys = array();

/**
 * Generate
 *
 * Output the diarys.
 */

function Diary($SingleDiary)
{
    /** Get the information of the diary */
    $Title   = $SingleDiary['Title'];
    $Date    = $SingleDiary['Date'];
    $Content = $SingleDiary['Content'];
    $MD5     = $SingleDiary['MD5'];
    
    /** Convert the date of the diary */
    $Month  = date('F' , $Date);
    $Day    = date('j', $Date);
    $DayEnd = date('S', $Date);
    $Week   = date('l' , $Date);
    
    echo <<<EOF
    <div class="g-2">
        <div class="diary-date shw-e rd-4">
            <div class="month">$Month</div>
            <div class="date">$Day<span class="end">$DayEnd</span></div>
            <div class="week">$Week</div>
        </div>
    </div>
    <div class="diary-container word-break shw-e g-9 rd-4 text-left border-box">
        <h3>$Title</h3>
        <div class="content">
            $Content
        </div>
    </div>
    
EOF;
}




/**
 * Scan 
 *
 * Get all the diary files
 */

$OS = (DIRECTORY_SEPARATOR == '\\') ? 'Windows' : 'Linux';

foreach(glob(__DIR__ . '/diary/*.txt') as $Diary)
{
    /** The file name is the title of this diary */
    /** Now we remove the path first, next is the file extension */
    $Title = ($OS == 'Linux') ? preg_replace('/\.\w*$/', '', preg_replace('/^.+[\\\\\\/]/', '', $Diary))
                              : iconv('BIG5', 'UTF-8', preg_replace('/\.\w*$/', '', preg_replace('/^.+[\\\\\\/]/', '', $Diary)));

    /** Get the content */
    $Content = nl2br(htmlspecialchars(file_get_contents($Diary)));
    
    /** Get the unix timestramp of the file */
    $Date = filemtime($Diary);
    
    /** Get the md5 of the file */
    $MD5 = md5_file($Diary);
    
    /** Push to the diary collection */
    array_push($Diarys, array('Title'   => $Title,
                              'Content' => $Content,
                              'Date'    => $Date,
                              'MD5'     => $MD5));
}




/**
 * Sort
 *
 * Sort the posts by last modif time.
 */

$Modif = array();

foreach($Diarys as $Key => $Val)
    $Modif[$Key] = $Val['Date'];

array_multisort($Modif, SORT_DESC, $Diarys);
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="css/tocas.css">
<link rel="stylesheet" href="css/filary.css">
<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;subset=latin" rel="stylesheet">
<title>Filary</title>
</head>
<body>
    
<nav class="shw-e center"><h1>Filary</h1></nav>
    
<section>
    <?php if(empty($Diarys)) { //If there's no any diary now ?>
    
    <div id="empty" class="table fill">
            <div class="table-cell center">
                <p>目前沒有任何日記。</p>
                <p>There's no any diary now.</p>
            </div>
        </div>
    
    <?php } else { //If there's a diary or many of them, then we echo them ?>
    
    <div class="row center">
        <?php foreach($Diarys as $Single) Diary($Single); ?>
    </div>
    
    <?php } ?>
</section> 
</body>
</html>
