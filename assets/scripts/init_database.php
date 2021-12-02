<?php
$config = include('./config/database.php');
$files = [
    0 => 'create_database',
    1 => 'init_data'
];

if($config['dbname'] != '') {
    for ($i = 0;$i < count($files);$i++) {
        $filename = './assets/sql/' . $files[$i] . '.sql';
        $command = "mysql --user={$config['user']} --password='{$config['password']}' " .
            "-h {$config['host']} ";
        if($i > 0)
            $command .= $config['dbname'];
        $output = shell_exec($command . " < {$filename}");
        //echo $output;
    }
}