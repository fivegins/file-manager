<?php

    use Librarys\File\FileInfo;

    define('LOADED', 1);
    require_once('global.php');

    if ($appUser->isLogin() == false)
        $appAlert->danger(lng('login.alert.not_login'), ALERT_LOGIN, env('app.http.host') . '/user/login.php');
    else if ($appMysqlConfig->get('mysql_name') != null)
        $appAlert->danger(lng('mysql.list_database.alert.mysql_is_not_connect_root', 'name', $appMysqlConnect->getName()), ALERT_MYSQL_LIST_TABLE, 'list_table.php');

    $title  = lng('mysql.info_database.title_page');
    $themes = [ env('resource.theme.mysql') ];
    $appAlert->setID(ALERT_MYSQL_INFO_DATABASE);
    require_once(ROOT . 'incfiles' . SP . 'header.php');

    // Clone in phpmyadmin
    $fetchAssoc = $appMysqlConnect->fetchAssoc(
        'SELECT `schemata`.*,
                COUNT(`tables`.`TABLE_SCHEMA`)  AS `SCHEMA_TABLES`,
                SUM(`tables`.`TABLE_ROWS`)      AS `SCHEMA_TABLE_ROWS`,
                SUM(`tables`.`DATA_LENGTH`)     AS `SCHEMA_DATA_LENGTH`,
                SUM(`tables`.`MAX_DATA_LENGTH`) AS `SCHEMA_MAX_DATA_LENGTH`,
                SUM(`tables`.`INDEX_LENGTH`)    AS `SCHEMA_INDEX_LENGTH`,
                SUM(`tables`.`DATA_LENGTH` + `tables`.`INDEX_LENGTH`)
                                                AS `SCHEMA_LENGTH`,
                SUM(`tables`.`DATA_FREE`)       AS `SCHEMA_DATA_FREE`
        FROM      `information_schema`.`SCHEMATA` `schemata`
        LEFT JOIN `information_schema`.`TABLES` `tables`
        ON        BINARY `tables`.`TABLE_SCHEMA` = BINARY `schemata`.`SCHEMA_NAME`
        WHERE     `schemata`.`SCHEMA_NAME`="' . $appMysqlConnect->getName() . '"'
    );

    $fetchAssoc['SCHEMA_TABLES']       = intval($fetchAssoc['SCHEMA_TABLES']);
    $fetchAssoc['SCHEMA_TABLE_ROWS']   = intval($fetchAssoc['SCHEMA_TABLE_ROWS']);
    $fetchAssoc['SCHEMA_DATA_LENGTH']  = intval($fetchAssoc['SCHEMA_DATA_LENGTH']);
    $fetchAssoc['SCHEMA_INDEX_LENGTH'] = intval($fetchAssoc['SCHEMA_INDEX_LENGTH']);
    $fetchAssoc['SCHEMA_LENGTH']       = intval($fetchAssoc['SCHEMA_LENGTH']);
?>

    <?php echo $appAlert->display(); ?>

    <ul class="mysql-info">
        <li class="title">
            <span><?php echo lng('mysql.info_database.title_page'); ?></span>
        </li>
        <li class="label">
            <ul>
                <li><span><?php echo lng('mysql.info_database.label_name'); ?></span></li>
                <li><span><?php echo lng('mysql.info_database.label_collation'); ?></span></li>
                <li><span><?php echo lng('mysql.info_database.label_data'); ?></span></li>
                <li><span><?php echo lng('mysql.info_database.label_tables'); ?></span></li>
                <li><span><?php echo lng('mysql.info_database.label_rows'); ?></span></li>
                <li><span><?php echo lng('mysql.info_database.label_indexes'); ?></span></li>
                <li><span><?php echo lng('mysql.info_database.label_total'); ?></span></li>
            </ul>
        </li>
        <li class="value">
            <ul>
                <li><span><?php echo $appMysqlConnect->getName(); ?></span></li>
                <li><span><?php echo $fetchAssoc['DEFAULT_COLLATION_NAME']; ?></span></li>
                <li><span><?php echo FileInfo::sizeToString($fetchAssoc['SCHEMA_DATA_LENGTH']); ?></span></li>
                <li><span><?php echo $fetchAssoc['SCHEMA_TABLES']; ?></span></li>
                <li><span><?php echo $fetchAssoc['SCHEMA_TABLE_ROWS']; ?></span></li>
                <li><span><?php echo FileInfo::sizeToString($fetchAssoc['SCHEMA_INDEX_LENGTH']); ?></span></li>
                <li><span><?php echo FileInfo::sizeToString($fetchAssoc['SCHEMA_LENGTH']); ?></span></li>
            </ul>
        </li>
    </ul>

    <ul class="menu-action">
        <li>
            <a href="delete_database.php?<?php echo PARAMETER_DATABASE_URL; ?>=<?php echo $appMysqlConnect->getName(); ?>">
                <span class="icomoon icon-trash"></span>
                <span><?php echo lng('mysql.list_database.menu_action.delete_database'); ?></span>
            </a>
        </li>
        <li>
            <a href="rename_database.php?<?php echo PARAMETER_DATABASE_URL; ?>=<?php echo $appMysqlConnect->getName(); ?>">
                <span class="icomoon icon-edit"></span>
                <span><?php echo lng('mysql.list_database.menu_action.rename_database'); ?></span>
            </a>
        </li>
        <li>
            <a href="create_database.php">
                <span class="icomoon icon-plus"></span>
                <span><?php echo lng('mysql.list_database.menu_action.create_database'); ?></span>
            </a>
        </li>
        <li>
            <a href="disconnect.php">
                <span class="icomoon icon-cord"></span>
                <span><?php echo lng('mysql.home.menu_action.disconnect'); ?></span>
            </a>
        </li>
    </ul>

<?php require_once(ROOT . 'incfiles' . SP . 'footer.php'); ?>