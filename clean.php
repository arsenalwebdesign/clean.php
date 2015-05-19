<?php

/* Deivison Arthur - Script para limpar cache e log no Magento 1.7*/
/* Jonatan Machado - Script para limpar cache e log no Magento editado com css e testado na 1.9*/

$xml = simplexml_load_file('./app/etc/local.xml', NULL, LIBXML_NOCDATA);

$db['host'] = $xml->global->resources->default_setup->connection->host;
$db['name'] = $xml->global->resources->default_setup->connection->dbname;
$db['user'] = $xml->global->resources->default_setup->connection->username;
$db['pass'] = $xml->global->resources->default_setup->connection->password;
$db['pref'] = $xml->global->resources->db->table_prefix;

/* http://seushop.com.br/clean.php?clean=log */
if($_GET['clean'] == 'log') clean_log_tables();

/* http://seushop.com.br/clean.php?clean=var */
if($_GET['clean'] == 'var') clean_var_directory();

/* http://seushop.com.br/clean.php?geral */
if(isset($_GET['geral'])){
  clean_log_tables();
  clean_var_directory();
};
function clean_log_tables() {
  global $db;
	
	$tables = array(
    'adminnotification_inbox',
    'aw_core_logger',
    'dataflow_batch_export',
    'dataflow_batch_import',
    'log_customer',
    'log_quote',
    'log_summary',
    'log_summary_type',
    'log_url',
    'log_url_info',
    'log_visitor',
    'log_visitor_info',
    'log_visitor_online',
    'index_event',
    'report_event',
    'report_viewed_product_index',
    'report_compared_product_index',
    'catalog_compare_item',
    'catalogindex_aggregation',
    'catalogindex_aggregation_tag',
    'catalogindex_aggregation_to_tag' 			  
	);
	
	mysql_connect($db['host'], $db['user'], $db['pass']) or die(mysql_error());
	mysql_select_db($db['name']) or die(mysql_error());
  echo '<div class="title">Tabelas de log limpas</div>';
  echo'<div class="clean_log_tables"><ul>';
	foreach($tables as $v => $k) {
		@mysql_query('TRUNCATE `'.$db['pref'].$k.'`');
        echo '<li class="clean_log_tables_list">Tabela <strong>'.$db['pref'].$k.'</strong> limpa! - <span>OK</span></li>';
	}
    echo'</ul></div>';

}

function clean_var_directory() {
	$dirs = array(
    'downloader/.cache/*',
    'downloader/pearlib/cache/*',
    'downloader/pearlib/download/*',
    'media/css/',
    'media/css_secure/',
    'media/import/',
    'media/js/',
    'var/cache/',
    'var/locks/',
    'var/log/',
    'var/report/',
    'var/session/',
    'includes/src/*',
    'var/tmp/'
	);
  echo '<div class="title">Diret&oacute;rio var limpo</div>';
  echo'<div class="clean_var_directory"><ul>';
	foreach($dirs as $v => $k) {
		exec('rm -rf '.$k);
        echo '<li class="clean_var_directory_list">Diret&oacute;rio <strong>'.$k.'</strong> Excluido! - <span>OK</span></li>';
	}
    echo'</ul></div>';

    echo '<div class="title">Sistema limpo com sucesso!</div>';
}

echo '<style type="text/css">
.clean_log_tables {
  margin: 0 auto;
  width: 500px;
  font-family: arial;
  color: #555;
}
.clean_var_directory {
  margin: 0 auto;
  width: 500px;
  font-family: arial;
  color: #555;
}
li.clean_log_tables_list {
  list-style: none;
  border: 1px #CCCCCC solid;
  margin-bottom: 3px;
  padding: 3px;
  border-radius: 3px;
}
li.clean_var_directory_list {
  list-style: none;
  border: 1px #CCCCCC solid;
  margin-bottom: 3px;
  padding: 3px;
  border-radius: 3px;
}
li.clean_log_tables_list span {
  font-weight: bold;
  color: #a5c17b;
}
li.clean_var_directory_list span {
  font-weight: bold;
  color: #a5c17b;
}
.title {
  margin: 0 auto;
  width: 94%;
  font-family: arial;
  text-align: center;
  padding: 3%;
  font-size: 30px;
  color: #555;
  border-top: #a5c17b 5px solid;
}
</style>';
