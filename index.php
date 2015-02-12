<?php 
date_default_timezone_set('Asia/Taipei');

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

/** Get all the diary files */
foreach(glob(__DIR__ . '/diary/*.txt') as $Diary)
{
    /** The file name is the title of this diary */
    $Title = basename($Diary, '.txt');
    
    /** Get the content */
    $Content = nl2br(file_get_contents($Diary));
    
    /** Get the unix timestramp of the file */
    $Date = filemtime($Diary);
    
    /** Get the md5 of the file */
    $MD5 = md5_file($Diary);
    
    /** Push to the diary collection */
    $Diarys[] = ['Title'   => $Title,
                 'Content' => $Content,
                 'Date'    => $Date,
                 'MD5'     => $MD5];
}



?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="i/css/tocas.css">
<link rel="stylesheet" href="i/css/meowary.css">
<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;subset=latin" rel="stylesheet">
<title>My Filary</title>
</head>
    
<body>
<nav class="shw-e center">
    <h1>Filary</h1>
</nav>
<section>
    <p>&nbsp;</p>
    <div class="row center">
    <?php foreach($Diarys as $Single) Diary($Single); ?>
    </div>
</section>
    
    
</body>
</html>