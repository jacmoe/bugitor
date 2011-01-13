<?php
// users
//try{
//            $dbh=new PDO('mysql:host=127.0.0.1;dbname=tracker','root','');
//            $dbh2=new PDO('mysql:host=127.0.0.1;dbname=ogitorbugs','root','');
//            $new_query = $dbh2->prepare('INSERT INTO bug_users (id, username, password, email, activkey, createtime, lastvisit, superuser, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
//            $query = $dbh->query('SELECT * FROM users');
//            foreach($query as $row){
//                        echo 'ID: '.$row['id']
//                        .' Name: '.$row['username']
//                        .' Password: '.$row['password']
//                        .' Email: '.$row['email']
//                        .'<br />';
//                $new_query->execute(array($row['id'],
//                        $row['username'],
//                        $row['password'],
//                        $row['email'],
//                        '', /*$row['activkey'],*/
//                        strtotime($row['created']),
//                        strtotime($row['last_login_on']),
//                        '0',//$row['superuser'],
//                        '1'//$row['status']
//                        ));
//            }
//}
//catch(PDOException $e){
//            echo 'Error : '.$e->getMessage();
//            exit();
//}

// projects
//try{
//            $dbh=new PDO('mysql:host=127.0.0.1;dbname=tracker','root','');
//            $dbh2=new PDO('mysql:host=127.0.0.1;dbname=ogitorbugs','root','');
//            $new_query = $dbh2->prepare('INSERT INTO bug_project (
//                id, name, description,
//                homepage, public, created,
//                modified, identifier) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
//            $query = $dbh->query('SELECT * FROM projects');
//            foreach($query as $row){
//                        echo 'ID: '.$row['id']
//                        .' Name: '.$row['name']
//                        .' Description: '.$row['description']
//                        .' Homepage: '.$row['homepage']
//                        .' Public: '.$row['is_public']
//                        .' Created: '.$row['created_on']
//                        .' Modified: '.$row['updated_on']
//                        .' Identifier: '.$row['identifier']
//                        .'<br />';
//                $new_query->execute(array($row['id'],
//                        $row['name'],
//                        $row['description'],
//                        $row['homepage'],
//                        $row['is_public'],
//                        $row['created_on'],
//                        $row['updated_on'],
//                        $row['identifier'],
//                        ));
//            }
//}
//catch(PDOException $e){
//            echo 'Error : '.$e->getMessage();
//            exit();
//}

// trackers
//try{
//            $dbh=new PDO('mysql:host=127.0.0.1;dbname=tracker','root','');
//            $dbh2=new PDO('mysql:host=127.0.0.1;dbname=ogitorbugs','root','');
//            $new_query = $dbh2->prepare('INSERT INTO bug_version (
//                id, project_id, name,
//                description, effective_date, created,
//                modified) VALUES (?, ?, ?, ?, ?, ?, ?)');
//            $query = $dbh->query('SELECT * FROM versions');
//            foreach($query as $row){
//                        echo 'ID: '.$row['id']
//                        .' Project ID: '.$row['project_id']
//                        .' Name: '.$row['name']
//                        .' Description: '.$row['description']
//                        .' Effective Date: '.$row['effective_date']
//                        .' Created: '.$row['created_on']
//                        .' Modified: '.$row['updated_on']
//                        .'<br />';
//                $new_query->execute(array($row['id'],
//                        $row['project_id'],
//                        $row['name'],
//                        $row['description'],
//                        $row['effective_date'],
//                        $row['created_on'],
//                        $row['updated_on'],
//                        ));
//            }
//}
//catch(PDOException $e){
//            echo 'Error : '.$e->getMessage();
//            exit();
//}

function get_status_id($number) {
    $return = '';
    switch ($number) {
        case 1:
            $return = 'swIssue/new';
            break;
        case 2:
            $return = 'swIssue/assigned';
            break;
        case 3:
            $return = 'swIssue/assigned';
            break;
        case 4:
            $return = 'swIssue/resolved';
            break;
        case 5:
            $return = 'swIssue/rejected';
            break;
        default:
            $return = 'swIssue/new';
            break;
    }
    return $return;
}

function get_is_closed($number) {
    $return = 0;
    switch ($number) {
        case 1:
        case 2:
        case 3:
            $return = 0;
            break;
        case 4:
        case 5:
            $return = 1;
            break;
        default:
            $return = 0;
            break;
    }
    return $return;
}

// issues
//try{
//            $dbh=new PDO('mysql:host=127.0.0.1;dbname=tracker','root','');
//            $dbh2=new PDO('mysql:host=127.0.0.1;dbname=ogitorbugs','root','');
//            $new_query = $dbh2->prepare('INSERT INTO bug_issue (
//                id, tracker_id, project_id,
//                subject, description, issue_category_id,
//                user_id, issue_priority_id, version_id,
//                assigned_to, created, modified,
//                done_ratio, status, closed,
//                pre_done_ratio, updated_by, last_comment
//                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
//            $query = $dbh->query('SELECT * FROM issues');
//            foreach($query as $row){
//                        echo 'ID: '.$row['id']
//                        .'<br />';
//                $new_query->execute(array($row['id'],
//                        $row['tracker_id'],
//                        $row['project_id'],
//                        $row['subject'],
//                        $row['description'],
//                        $row['issue_category_id'],
//                        $row['user_id'],
//                        $row['issue_priority_id'],
//                        $row['version_id'],
//                        $row['assignedto'],
//                        date("Y-m-d\TH:i:s\Z", strtotime($row['created_on'])),
//                        date("Y-m-d\TH:i:s\Z", strtotime($row['updated_on'])),
//                        $row['done_ratio'],
//                        get_status_id($row['issue_status_id']),
//                        get_is_closed($row['issue_status_id']),
//                        0,
//                        $row['user_id'],
//                        ''//$row['last_comment'],
//                        ));
//            }
//}
//catch(PDOException $e){
//            echo 'Error : '.$e->getMessage();
//            exit();
//}

// comments
//try{
//            $dbh=new PDO('mysql:host=127.0.0.1;dbname=tracker','root','');
//            $dbh2=new PDO('mysql:host=127.0.0.1;dbname=ogitorbugs','root','');
//            $new_query = $dbh2->prepare('INSERT INTO bug_comment (
//                id, content, issue_id,
//                created, create_user_id, modified, update_user_id
//                ) VALUES (?, ?, ?,
//                ?, ?, ?, ?)');
//            $query = $dbh->query('SELECT * FROM journals');
//            foreach($query as $row){
////                        $details_query = $dbh->prepare('SELECT journal_id, property, prop_key, old_value, value FROM journal_details WHERE journal_id = :journal_id');
////                        $details_query->execute(array(':journal_id' => $row['id']));
////                        $results = $details_query->fetchAll();
////                        foreach($results as $result) {
////                            echo 'Journal id: ' . $result['journal_id'] . '<br>';
////                            echo 'Property: ' . $result['property'] . '<br>';
////                            echo 'Property Key: ' . $result['prop_key'] . '<br>';
////                            echo 'Old Value: ' . $result['old_value'] . '<br>';
////                            echo 'Value: ' . $result['value'] . '<br>';
////                        }
//                        echo 'ID: '.$row['id']
//                        .' Issue ID: '.$row['issue_id']
//                        .' User ID: '.$row['user_id']
//                        .' Notes: <pre>'.$row['notes'].'</pre>'
//                        .' Created: '.$row['created_on']
//                        .'<br />';
//                $new_query->execute(array($row['id'],
//                        ($row['notes']!=='') ? $row['notes'] : '_No comments for this change_',
//                        $row['issue_id'],
//                        date("Y-m-d\TH:i:s\Z", strtotime($row['created_on'])),
//                        $row['user_id'],
//                        date("Y-m-d\TH:i:s\Z", strtotime($row['created_on'])),
//                        $row['user_id'],
//                        ));
//            }
//}
//catch(PDOException $e){
//            echo 'Error : '.$e->getMessage();
//            exit();
//}

function get_version($number) {
    $version = '';
    switch ($number) {
        case '23':
            $version = '0.3.0';
            break;
        case '24':
            $version = '0.3.1';
            break;
        case '25':
            $version = '0.3.2';
            break;
        case '26':
            $version = '0.3.3';
            break;
        case '27':
            $version = '0.3.4';
            break;
        case '34':
            $version = '0.4.0';
            break;
        case '35':
            $version = '0.5.0';
            break;
        case '36':
            $version = '0.2.0';
            break;
        case '37':
            $version = '0.3.0';
            break;
        case '38':
            $version = '0.4.2';
            break;
        default:
            $version = $number;
            break;
    }
    return $version;
}

function get_project($number) {
    $project = '';
    switch ($number) {
        case '2':
            $project = 'Bugitor';
            break;
        default:
            $project = 'Ogitor';
            break;
    }
    return $project;
}

function get_user($number) {
    $user = '';
    switch ($number) {
        case '1':
            $user = 'Jacmoe';
            break;
        case '5':
            $user = 'Spacegaier';
            break;
        case '6':
            $user = 'Ismail_tarim';
            break;
        case '7':
            $user = 'Xadhoom';
            break;
        case '8':
            $user = 'Fusion44';
            break;
        case '9':
            $user = 'Squirell';
            break;
        case '10':
            $user = 'XenoPhoenix';
            break;
        case '11':
            $user = 'Wizzler';
            break;
        case '12':
            $user = 'Guru';
            break;
        case '13':
            $user = 'Linfuyong';
            break;
        case '14':
            $user = 'Boogy';
            break;
        case '15':
            $user = 'Acolyte';
            break;
        case '16':
            $user = 'Shockeye';
            break;
        case '17':
            $user = 'Svenstaro';
            break;
        case '18':
            $user = 'Hatboyzero';
            break;
        case '19':
            $user = 'Tinoshi';
            break;
        case '20':
            $user = 'Tinnus';
            break;
        case '21':
            $user = 'Altren';
            break;
        case '22':
            $user = 'Andrija_petrovic';
            break;
        case '23':
            $user = 'Andrew';
            break;
        case '24':
            $user = 'Girivs';
            break;
        case '25':
            $user = 'Zhk_tiger';
            break;
        case '26':
            $user = 'Mk0';
            break;
        case '27':
            $user = 'Themouse';
            break;
        default:
            $user = '';
            break;
    }
    return $user;
}

function get_tracker($number) {
    $tracker = '';
    switch ($number) {
        case '1':
            $tracker = 'Bug';
            break;
        default:
            $tracker = 'Feature';
            break;
    }
    return $tracker;
}

function get_status($number) {
    $status = '';
    switch ($number) {
        case 1:
            $status = 'New';
            break;
        case 2:
            $status = 'Feedback';
            break;
        case 3:
            $status = 'Assigned';
            break;
        case 4:
            $status = 'Resolved';
            break;
        case 5:
            $status = 'Rejected';
            break;
        default:
            $status = 'New';
            break;
    }
    return $status;
}

function get_priority($number) {
    $priority = '';
    switch ($priority) {
        case '1':
            $priority = 'Low';
            break;
        case '2':
            $priority = 'Normal';
            break;
        case '3':
            $priority = 'High';
            break;
        default:
            $priority = 'Normal';
            break;
    }
    return $priority;
}

function get_prop_name($key) {
    $name = '';
    switch ($key) {
        case 'status_id':
            $name = 'Status';
            break;
        case 'issue_status_id':
            $name = 'Status';
            break;
        case 'done_ratio':
            $name = 'Done Ratio';
            break;
        case 'priority_id':
            $name = 'Priority';
            break;
        case 'issue_priority_id':
            $name = 'Priority';
            break;
        case 'assigned_to_id':
            $name = 'Assigned To';
            break;
        case 'assignedto':
            $name = 'Assigned To';
            break;
        case 'fixed_version_id':
            $name = 'Version';
            break;
        case 'version_id':
            $name = 'Version';
            break;
        case 'category_id':
            $name = 'Category';
            break;
        case 'issue_category_id':
            $name = 'Category';
            break;
        case 'tracker_id':
            $name = 'Tracker';
            break;
        case 'project_id':
            $name = 'Project';
            break;
        default:
            $name = '';
            break;
    }
    return $name;
}

function get_value_propkey($key, $value) {
    switch ($key) {
        case 'status_id':
            $value = get_status($value);
            break;
        case 'issue_status_id':
            $value = get_status($value);
            break;
        case 'done_ratio':
            $value = (int)$value;
            break;
        case 'priority_id':
            $value = get_priority($value);
            break;
        case 'issue_priority_id':
            $value = get_priority($value);
            break;
        case 'assigned_to_id':
            $value = get_user($value);
            break;
        case 'assignedto':
            $value = get_user($value);
            break;
        case 'fixed_version_id':
            $value = get_version($value);
            break;
        case 'version_id':
            $value = get_version($value);
            break;
        case 'category_id':
            $value = $value;
            break;
        case 'issue_category_id':
            $value = $value;
            break;
        case 'tracker_id':
            $value = get_tracker($value);
            break;
        case 'project_id':
            $value = get_project($value);
            break;
        default:
            $value = '';
            break;
    }
    return $value;
}

// commentdetails
//$to_insert = array();
//
//try{
//            $dbh=new PDO('mysql:host=127.0.0.1;dbname=tracker','root','');
//            $query = $dbh->query('SELECT * FROM journals');
//            foreach($query as $row){
//                        $details_query = $dbh->prepare('SELECT journal_details.id, journal_id, property, prop_key, old_value, value FROM journal_details WHERE journal_id = :journal_id');
//                        $details_query->execute(array(':journal_id' => $row['id']));
//                        $results = $details_query->fetchAll();
//                        foreach($results as $result) {
//                            $change = '';
//                            if($result['property'] === 'attr') {
//                                if($result['old_value']) {
//                                    if($result['value']){
//                                        $change = '<b>'. get_prop_name($result['prop_key']) . '</b> changed from <i>' . get_value_propkey($result['prop_key'], $result['old_value']) . '</i> to <i>' . get_value_propkey($result['prop_key'], $result['value']) . '</i>';
//                                    } else {
//                                        $change =  '<b>'. get_prop_name($result['prop_key']) . '</b> deleted. (<s><i>' . get_value_propkey($result['prop_key'], $result['old_value']) . '</i></s>)';
//                                    }
//                                } else {
//                                    $change =  '<b>'. get_prop_name($result['prop_key']) . '</b> set to <i>' . get_value_propkey($result['prop_key'], $result['value']) . '</i>';
//                                }
//                                $insert['id'] = $result['id'];
//                                $insert['comment_id'] = $result['journal_id'];
//                                $insert['change'] = $change;
//                                $to_insert[] = $insert;
//                            }
//                        }
//            }
//}
//catch(PDOException $e){
//            echo 'Error : '.$e->getMessage();
//            exit();
//}
//
//try{
//    $dbh3=new PDO('mysql:host=127.0.0.1;dbname=ogitorbugs','root','');
//    $new_queryy = $dbh3->prepare('INSERT INTO bug_comment_detail (
//        comment_id, `change`
//        ) VALUES (? , ?)');
//
//    foreach($to_insert as $result)
//    {
//        echo 'inserting : ' . $result['id'] . ' , ' . $result['comment_id'] . ' , ' . $result['change'] . '<br/>';
//        if($new_queryy->execute(array($result['comment_id'], $result['change'])))
//        {
//            echo 'fine<br/>';
//        }else{
//            echo 'what the fuck?<br/>';
//        }
//    }
//}
//catch(PDOException $e){
//            echo 'Error : '.$e->getMessage();
//            exit();
//}

// watchers
//try{
//            $dbh=new PDO('mysql:host=127.0.0.1;dbname=tracker','root','');
//            $dbh2=new PDO('mysql:host=127.0.0.1;dbname=ogitorbugs','root','');
//            $new_query = $dbh2->prepare('INSERT INTO bug_watcher (
//                issue_id, user_id) VALUES (?, ?)');
//            $query = $dbh->query('SELECT * FROM watchers');
//            foreach($query as $row){
//                        echo ' Issue ID: '.$row['issue_id']
//                        .' User ID: '.$row['user_id']
//                        .'<br />';
//                $new_query->execute(array($row['issue_id'],
//                        $row['user_id'],
//                        ));
//            }
//}
//catch(PDOException $e){
//            echo 'Error : '.$e->getMessage();
//            exit();
//}
